<?php

namespace App\Http\Controllers;

use App\Models\About;
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
        return view('front.index', $data);
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
        return view('front.details', compact('property', 'about', 'agen'));
    }

    public function category(Category $category)
    {
        $category->load(['properties','propertyType']);
        $data = $this->propertyService->getCategoriesAndCities();
        $data['category'] = $category;
        return view('front.category', $data);
    }

    public function storeCustomer(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
            'property_id' => 'required|exists:properties,id'
        ], [
            'name.required' => 'Nama wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'email.email' => 'Format email tidak valid',
            'property_id.required' => 'Property tidak valid',
            'property_id.exists' => 'Property tidak ditemukan'
        ]);

        try {
            // Ambil data property untuk pesan WhatsApp
            $property = Property::find($validated['property_id']);
            $agen = User::role('agen')->first();

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

            // Format nomor WhatsApp agen (hilangkan karakter non-digit dan tambah 62)
            $agenPhone = $this->formatPhoneNumber($agen->phone);

            // URL WhatsApp Web
            $whatsappUrl = "https://web.whatsapp.com/send?phone={$agenPhone}&text=" . urlencode($waMessage);

            // Redirect ke WhatsApp Web
            return redirect()->away($whatsappUrl);

        } catch (\Exception $e) {
            // Redirect dengan pesan error jika ada masalah
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
}
