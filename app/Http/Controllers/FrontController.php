<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Blog;
use App\Models\Why;
use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Models\ManageCustomer;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Facility;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\PropertyPhoto;
use App\Models\PropertyFacility;

class FrontController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request)
    {
        $data = $this->propertyService->getCategoriesAndCities();
        $agen = User::role('agen')->limit(3)->get();

        // Get filter parameters
        $statusFilter = $request->get('status');
        $sortBy = $request->get('sort_by');

        // Build query
        $query = Property::where('status_active', 'Active')
            ->where(function ($q) {
                $q->whereNull('status_iklan')
                ->orWhere('status_iklan', 'Active');
            });
        // Apply status filter if provided
        if ($statusFilter && in_array($statusFilter, ['Rent', 'Sale'])) {
            if ($statusFilter === 'Rent') {
                $query->where('status_listing', 'For Rent');
            } elseif ($statusFilter === 'Sale') {
                $query->where('status_listing', 'For Sale');
            }
        }

        // Apply sorting based on price
        if ($sortBy && in_array($sortBy, ['price_asc', 'price_desc'])) {
            if ($sortBy === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sortBy === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        } else {
            // Default sorting (latest first)
            $query->latest();
        }

        $propertie = $query->paginate(6);

        // Preserve query parameters in pagination links
        $propertie->appends($request->query());

        // Tambahkan pagination untuk blog dengan parameter berbeda
        $blog = Blog::latest()->paginate(3, ['*'], 'blog_page');

        $why = Why::all();
        return view('front.index', array_merge($data, [
            'agen' => $agen,
            'propertie' => $propertie,
            'blog' => $blog,
            'why' => $why,
            'currentStatus' => $statusFilter, // Pass current filter to view
            'currentSort' => $sortBy // Pass current sort to view
        ]));
    }
    public function search(Request $request)
    {
        $data = $this->propertyService->searchProperties($request->all());
        return view('front.search', array_merge($data));
    }

    public function details(Property $property)
    {
        $property = $this->propertyService->getPropertyDetails($property);
        $about = About::first();
        $agen = User::role('agen')->first();


        $propertyRelated = Property::where('category_id', $property->category_id)
            ->where('id', '!=', $property->id)
            ->where('status_active', 'Active')
            ->limit(4)
            ->get();

        return view('front.details', compact('property', 'about', 'agen', 'propertyRelated'));
    }



    public function category(Category $category)
    {
        $category->load(['properties', 'propertyType']);
        $data = $this->propertyService->getCategoriesAndCities();
        $data['category'] = $category;
        return view('front.category', $data);
    }

    public function blog(Blog $blog)
    {
        $about = About::first();
        return view('front.blog_detail', compact('about', 'blog'));
    }

    public function about()
    {
        $about = About::first();
        return view('front.about', compact('about'));
    }

    public function blogAll()
    {
        $data = $this->propertyService->getCategoriesAndCities();

        $agen = User::role('agen')->limit(3)->get();
        $propertie = Property::where('status_active', 'Active')->get();
        $blog = Blog::paginate(6);
        return view('front.blog', array_merge($data, [
            'agen' => $agen,
            'propertie' => $propertie,
            'blog' => $blog
        ]));
    }

    // public function storeCustomer(Request $request)
    // {
    //     // Validasi input
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'phone' => 'required|string|max:20',
    //         'email' => 'nullable|email|max:255',
    //         'message' => 'nullable|string|max:1000',
    //         'property_id' => 'required|exists:properties,id',
    //         'agen_phone' => 'required|string|max:20', // tambahkan validasi agen_phone
    //     ], [
    //         'name.required' => 'Nama wajib diisi',
    //         'phone.required' => 'Nomor telepon wajib diisi',
    //         'email.email' => 'Format email tidak valid',
    //         'property_id.required' => 'Property tidak valid',
    //         'property_id.exists' => 'Property tidak ditemukan',
    //         'agen_phone.required' => 'Nomor telepon agen wajib ada',
    //     ]);

    //     try {
    //         // Ambil data property
    //         $property = Property::find($validated['property_id']);

    //         // Simpan data customer
    //         $customer = ManageCustomer::create([
    //             'name' => $validated['name'],
    //             'phone' => $validated['phone'],
    //             'email' => $validated['email'] ?? null,
    //             'message' => $validated['message'] ?? null,
    //             'property_id' => $validated['property_id'],
    //             'status' => 'pending'
    //         ]);

    //         // Format pesan WhatsApp
    //         $waMessage = $this->formatWhatsAppMessage($validated, $property);

    //         // Gunakan nomor agen dari request hidden input
    //         $agenPhone = $this->formatPhoneNumber($validated['agen_phone']);

    //         // URL WhatsApp Web
    //         $whatsappUrl = "https://web.whatsapp.com/send?phone={$agenPhone}&text=" . urlencode($waMessage);

    //         return redirect()->away($whatsappUrl);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
    //     }
    // }

    // /**
    //  * Format pesan WhatsApp
    //  */
    // private function formatWhatsAppMessage($data, $property)
    // {
    //     $message = "Halo, saya tertarik dengan properti berikut:\n\n";
    //     $message .= "🏠 *{$property->name}*\n";
    //     $message .= "💰 Harga: Rp " . number_format($property->price, 0, ',', '.') . "\n\n";

    //     $message .= "📋 *Detail Kontak:*\n";
    //     $message .= "👤 Nama: {$data['name']}\n";
    //     $message .= "📞 Telepon: {$data['phone']}\n";

    //     if (!empty($data['email'])) {
    //         $message .= "📧 Email: {$data['email']}\n";
    //     }

    //     if (!empty($data['message'])) {
    //         $message .= "\n💬 *Pesan:*\n{$data['message']}\n";
    //     }

    //     $message .= "\nMohon informasi lebih lanjut mengenai properti ini. Terima kasih! 🙏";

    //     return $message;
    // }

    // /**
    //  * Preview pesan WhatsApp sebelum dikirim (method opsional)
    //  */
    // public function previewWhatsApp(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'phone' => 'required|string|max:20',
    //         'email' => 'nullable|email|max:255',
    //         'message' => 'nullable|string|max:1000',
    //         'property_id' => 'required|exists:properties,id'
    //     ]);

    //     $property = Property::find($validated['property_id']);
    //     $agen = User::role('agen')->first();
    //     $waMessage = $this->formatWhatsAppMessage($validated, $property);
    //     $agenPhone = $this->formatPhoneNumber($agen->phone);

    //     return response()->json([
    //         'message' => $waMessage,
    //         'agent_name' => $agen->name,
    //         'agent_phone' => $agen->phone,
    //         'whatsapp_url' => "https://web.whatsapp.com/send?phone={$agenPhone}&text=" . urlencode($waMessage)
    //     ]);
    // }

    // /**
    //  * Format nomor telepon untuk WhatsApp
    //  */
    // private function formatPhoneNumber($phone)
    // {
    //     // Hilangkan semua karakter selain angka
    //     $phone = preg_replace('/[^0-9]/', '', $phone);

    //     // Jika dimulai dengan 08, ganti dengan 628
    //     if (substr($phone, 0, 2) === '08') {
    //         $phone = '62' . substr($phone, 1);
    //     }
    //     // Jika dimulai dengan 8, tambah 62 di depan
    //     elseif (substr($phone, 0, 1) === '8') {
    //         $phone = '62' . $phone;
    //     }
    //     // Jika dimulai dengan +62, hilangkan +
    //     elseif (substr($phone, 0, 3) === '+62') {
    //         $phone = substr($phone, 1);
    //     }
    //     // Jika tidak dimulai dengan 62, tambah 62 di depan
    //     elseif (substr($phone, 0, 2) !== '62') {
    //         $phone = '62' . $phone;
    //     }

    //     return $phone;
    // }

    public function storeCustomer(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
            'property_id' => 'required|exists:properties,id',
            'agen_phone' => 'required|string|max:20', // tambahkan validasi agen_phone
        ], [
            'name.required' => 'Nama wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'email.email' => 'Format email tidak valid',
            'property_id.required' => 'Property tidak valid',
            'property_id.exists' => 'Property tidak ditemukan',
            'agen_phone.required' => 'Nomor telepon agen wajib ada',
        ]);

        try {
            // Ambil data property
            $property = Property::find($validated['property_id']);

            // Simpan data customer
            $customer = ManageCustomer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'message' => $validated['message'] ?? null,
                'property_id' => $validated['property_id'],
                'status' => 'pending'
            ]);

            // Format pesan WhatsApp (opsional untuk notifikasi internal atau log)
            $waMessage = $this->formatWhatsAppMessage($validated, $property);

            // Gunakan nomor agen dari request hidden input
            $agenPhone = $this->formatPhoneNumber($validated['agen_phone']);

            // Simpan log pesan atau proses internal lainnya jika diperlukan
            // Log::info("Customer inquiry received", ['customer_id' => $customer->id, 'message' => $waMessage]);

            // Redirect kembali ke halaman yang sama dengan pesan sukses
            return redirect()->back()->with('success', 'Pesan berhasil dikirim, kami akan menghubungi kembali untuk konfirmasi');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Format pesan WhatsApp (tetap dipertahankan untuk keperluan internal)
     */
    private function formatWhatsAppMessage($data, $property)
    {
        $message = "Halo, saya tertarik dengan properti berikut:\n\n";
        $message .= "🏠 *{$property->name}*\n";
        $message .= "💰 Harga: Rp " . number_format($property->price, 0, ',', '.') . "\n\n";

        $message .= "📋 *Detail Kontak:*\n";
        $message .= "👤 Nama: {$data['name']}\n";
        $message .= "📞 Telepon: {$data['phone']}\n";

        if (!empty($data['email'])) {
            $message .= "📧 Email: {$data['email']}\n";
        }

        if (!empty($data['message'])) {
            $message .= "\n💬 *Pesan:*\n{$data['message']}\n";
        }

        $message .= "\nMohon informasi lebih lanjut mengenai properti ini. Terima kasih! 🙏";

        return $message;
    }

    /**
     * Preview pesan WhatsApp sebelum dikirim (method opsional - bisa dihapus jika tidak digunakan)
     */
    public function previewWhatsApp(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
            'property_id' => 'required|exists:properties,id'
        ]);

        $property = Property::find($validated['property_id']);
        $agen = User::role('agen')->first();
        $waMessage = $this->formatWhatsAppMessage($validated, $property);
        $agenPhone = $this->formatPhoneNumber($agen->phone);

        return response()->json([
            'message' => $waMessage,
            'agent_name' => $agen->name,
            'agent_phone' => $agen->phone,
            'whatsapp_url' => "https://web.whatsapp.com/send?phone={$agenPhone}&text=" . urlencode($waMessage)
        ]);
    }

    /**
     * Format nomor telepon untuk WhatsApp (tetap dipertahankan jika masih diperlukan)
     */
    private function formatPhoneNumber($phone)
    {
        // Hilangkan semua karakter selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika dimulai dengan 08, ganti dengan 628
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        }
        // Jika dimulai dengan 8, tambah 62 di depan
        elseif (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }
        // Jika dimulai dengan +62, hilangkan +
        elseif (substr($phone, 0, 3) === '+62') {
            $phone = substr($phone, 1);
        }
        // Jika tidak dimulai dengan 62, tambah 62 di depan
        elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }
    public function contact()
    {
        $data = $this->propertyService->getCategoriesAndCities();

        $agen = User::role('agen')->limit(3)->get();
        $propertie = Property::where('status_active', 'Active')->get();
        return view('front.contact', array_merge($data, [
            'agen' => $agen,
            'propertie' => $propertie
        ]));
    }

    public function filterProperties(Request $request)
    {
        $statusFilter = $request->get('status');

        $query = Property::where('status_active', 'Active');

        if ($statusFilter && in_array($statusFilter, ['Rent', 'Sale'])) {
            if ($statusFilter === 'Rent') {
                $query->where('status_listing', 'For Rent');
            } elseif ($statusFilter === 'Sale') {
                $query->where('status_listing', 'For Sale');
            }
        }

        $propertie = $query->paginate(6);
        $propertie->appends($request->query());

        // Return partial view for AJAX
        $html = view('partials.property-list', compact('propertie'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $propertie->links()->render()
        ]);
    }


    public function iklan()
    {

        $propertyTypes = PropertyType::all();
        $categories = Category::all();
        $cities = City::all();
        $facilities = Facility::all();
        $about = About::first();

        return view('front.iklan', compact('about','propertyTypes', 'categories', 'cities', 'facilities'));
  
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            // Data pengiklan
            'name_iklan' => 'required|string|max:255',
            'email_iklan' => 'required|email|max:255',
            'phone_iklan' => 'required|string|max:20',
            
            // Data properti
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'property_type_id' => 'required|exists:property_types,id',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'about' => 'required|string',
            'paragraph' => 'nullable|string',
            'status_listing' => 'required|in:For Sale,For Rent',
            
            // Data tambahan
            'certificate' => 'nullable|in:SHM,HGB,IMB,Lainnya',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'electric' => 'nullable|integer|min:0',
            'land_area' => 'nullable|numeric|min:0',
            'building_area' => 'nullable|numeric|min:0',
            'map' => 'nullable|string',
            
            // File upload
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,gif|max:1024',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            
            // Fasilitas
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        DB::beginTransaction();
        
        try {
            // Upload thumbnail
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('properties', 'direct_storage');
            }

            // Buat property baru
            $property = Property::create([
                // Data pengiklan
                'name_iklan' => $request->name_iklan,
                'email_iklan' => $request->email_iklan,
                'phone_iklan' => $request->phone_iklan,
                'status_iklan' => 'Inactive', // Default status untuk iklan baru
                
                // Data properti
                'jenis' => 'Iklan',
                'name' => $request->name,
                'price' => $request->price,
                'property_type_id' => $request->property_type_id,
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'about' => $request->about,
                'paragraph' => $request->paragraph,
                'status_listing' => $request->status_listing,
                'status_active' => 'Inactive', // Default inactive sampai disetujui admin
                'thumbnail' => $thumbnailPath,
                
                // Data tambahan
                'certificate' => $request->certificate,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'electric' => $request->electric,
                'land_area' => $request->land_area,
                'building_area' => $request->building_area,
                'map' => $request->map,
            ]);

            // Upload foto tambahan
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPath = $photo->store('properties', 'direct_storage');
                    PropertyPhoto::create([
                        'property_id' => $property->id,
                        'photo' => $photoPath,
                    ]);
                }
            }

            // Simpan fasilitas
            if ($request->has('facilities') && is_array($request->facilities)) {
                foreach ($request->facilities as $facilityId) {
                    PropertyFacility::create([
                        'property_id' => $property->id,
                        'facility_id' => $facilityId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Iklan berhasil dibuat! Menunggu persetujuan admin.');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika ada error
            if ($thumbnailPath) {
                Storage::disk('direct_storage')->delete($thumbnailPath);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat iklan. Silakan coba lagi.')
                ->withInput();
        }
    }
}
