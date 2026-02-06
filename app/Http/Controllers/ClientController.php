<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\GoogleDriveService;



class ClientController extends Controller
{
    /**
     * FORM TAMBAH CLIENT
     */
    public function create()
    {
        $categories = Client::getCategories();

        return view('add-clients.create', compact('categories'));
    }

    /**
     * SIMPAN CLIENT + INVOICE
     */
/**
 * SIMPAN CLIENT + INVOICE
 */
public function store(Request $request)
{
    $request->validate([
        'nama_brand'   => 'required|string|max:255',
        'phone'        => 'required|string|max:20',
        'category'     => 'required|string',
        'start_date'   => 'required|date',
        'duration'     => 'required|integer|min:1',
        'email'        => 'nullable|email',
        'address'      => 'nullable|string',
        'notes'        => 'nullable|string',
        'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    DB::beginTransaction();

    try {
        $startDate   = Carbon::parse($request->start_date)->startOfDay();
        $expiredDate = $startDate->copy()->addMonths((int) $request->duration);

        // STATUS OTOMATIS
        $today = Carbon::today();

        if ($today->lt($startDate)) {
            $status = 'belumaktif';
        } elseif ($today->between($startDate, $expiredDate)) {
            $status = 'aktif';
        } else {
            $status = 'nonaktif';
        }

        $client = Client::create([
            'name'         => $request->name,
            'nama_brand'   => $request->nama_brand,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'category'     => $request->category,
            'start_date'   => $startDate,
            'expired_date' => $expiredDate,
            'status'       => $status,
            'notes'        => $request->notes,
        ]);

        // SIMPAN INVOICE KE DATABASE
        // SIMPAN INVOICE KE DATABASE
if ($request->hasFile('invoice_file')) {
    $file = $request->file('invoice_file');
    
    // Validasi ukuran file (max 10MB untuk aman)
    if ($file->getSize() > 10485760) { // 10MB
        throw new \Exception('File terlalu besar. Maksimal 10MB.');
    }
    
    // Baca file sebagai binary
    $fileContent = file_get_contents($file->getRealPath());
    
    // Kompresi jika gambar (optional)
    if (strpos($file->getMimeType(), 'image/') === 0) {
        // Kompres gambar dengan GD Library
        $image = imagecreatefromstring($fileContent);
        ob_start();
        imagejpeg($image, null, 85); // Kompresi 85%
        $fileContent = ob_get_clean();
        imagedestroy($image);
    }
    
    Invoice::create([
        'client_id'          => $client->id,
        'invoice_number'     => 'INV-' . now()->format('YmdHis'),
        'file_original_name' => $file->getClientOriginalName(),
        'file_mime_type'     => $file->getMimeType(),
        'file_size'          => strlen($fileContent), // Ukuran setelah kompresi
        'file_content'       => $fileContent,
        'invoice_date'       => now(),
    ]);
}


        DB::commit();

        return redirect()
            ->route('data-client.index')
            ->with('success', 'Client berhasil ditambahkan');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->with('error', 'Gagal menyimpan client: ' . $e->getMessage());
    }
}
}
