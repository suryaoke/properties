<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Property;
use App\Models\User;
use App\Models\ManageCustomer;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index()
{
    $data = $this->propertyService->getCategoriesAndCities();

    $agen = User::role('agen')->limit(3)->get();

    $propertie = Property::where('status_active', 'Active')->paginate(6);

    // Tambahkan pagination untuk blog dengan parameter berbeda
    $blog = Blog::latest()->paginate(6, ['*'], 'blog_page');

    return view('front.index', array_merge($data, [
        'agen' => $agen,
        'propertie' => $propertie,
        'blog' => $blog
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
        $blog = Blog::all();
        return view('front.blog', array_merge($data, [
            'agen' => $agen,
            'propertie' => $propertie,
            'blog' => $blog
        ]));
    }

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

            // Format pesan WhatsApp
            $waMessage = $this->formatWhatsAppMessage($validated, $property);

            // Gunakan nomor agen dari request hidden input
            $agenPhone = $this->formatPhoneNumber($validated['agen_phone']);

            // URL WhatsApp Web
            $whatsappUrl = "https://web.whatsapp.com/send?phone={$agenPhone}&text=" . urlencode($waMessage);

            return redirect()->away($whatsappUrl);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Format pesan WhatsApp
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
     * Preview pesan WhatsApp sebelum dikirim (method opsional)
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
     * Format nomor telepon untuk WhatsApp
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
}
