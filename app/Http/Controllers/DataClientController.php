<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\GoogleDriveService;
use App\Models\Invoice;


class DataClientController extends Controller
{
    /**
     * Halaman data klien
     */
public function index(Request $request)
{
    $search = $request->search;
    $status = $request->status;

    $clients = Client::query()

        ->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('nama_brand', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        })

        ->when($status, function ($q) use ($status) {
            $today = now()->startOfDay();

            if ($status === 'aktif') {
                $q->whereDate('start_date', '<=', $today)
                  ->whereDate('expired_date', '>=', $today);
            }

            if ($status === 'non aktif') {
                $q->whereDate('expired_date', '<', $today);
            }

            if ($status === 'belum aktif') {
                $q->whereDate('start_date', '>', $today);
            }
        })

        ->orderBy('created_at', 'desc')
        ->paginate(15)
        ->withQueryString();

    return view('data-client.index', compact('clients'));
}



    /**
     * Detail klien
     */
  public function show($id)
{
    $client = Client::with('invoices')->findOrFail($id);
    return view('data-client.show-client', compact('client'));
}


    /**
     * Form edit klien
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('data-client.edit-client', compact('client'));
    }

    /**
     * Update data klien (status BOLEH diubah manual)
     */
    /**
 * Update data klien
 */
public function update(Request $request, $id)
{
    $request->validate([
        'name'        => 'nullable|string|max:255',
        'nama_brand'  => 'nullable|string|max:255',
        'phone'       => 'nullable|string|max:20',
        'email'       => 'nullable|email',
        'address'     => 'nullable|string',
        'category'    => 'nullable|string',
        'status'      => 'nullable|in:aktif,non aktif,belum aktif',
        'invoice'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $client = Client::findOrFail($id);

    // ===== STATUS OTOMATIS DARI DB =====
    $today = now()->startOfDay();

    if ($client->start_date > $today) {
        $autoStatus = 'belum aktif';
    } elseif ($client->expired_date < $today) {
        $autoStatus = 'non aktif';
    } else {
        $autoStatus = 'aktif';
    }

    $client->update([
        'name'       => $request->name ?? $client->name,
        'nama_brand' => $request->nama_brand ?? $client->nama_brand,
        'phone'      => $request->phone ?? $client->phone,
        'email'      => $request->email ?? $client->email,
        'address'    => $request->address ?? $client->address,
        'notes'      => $request->notes ?? $client->notes,
        'category'   => $request->category ?? $client->category,
        'status'     => $request->status ?? $autoStatus,
    ]);

    // UPDATE INVOICE JIKA ADA FILE BARU
    // UPDATE INVOICE JIKA ADA FILE BARU
if ($request->hasFile('invoice')) {
    $file = $request->file('invoice');
    $fileContent = file_get_contents($file->getRealPath());

    $client->invoices()->updateOrCreate(
        ['client_id' => $client->id],
        [
            'invoice_number'     => $request->invoice_number 
                ?? $client->invoices()->latest()->first()?->invoice_number
                ?? 'INV-' . now()->format('YmdHis'),
            // HAPUS 'file_name'
            'file_original_name' => $file->getClientOriginalName(),
            'file_mime_type'     => $file->getMimeType(),
            'file_size'          => $file->getSize(),
            'file_content'       => $fileContent,
            'invoice_date'       => now(),
        ]
    );
}

    return redirect()
        ->route('data-client.index')
        ->with('success', 'Data klien berhasil diperbarui');
}


    /**
     * Update status saja (manual toggle)
     */
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:' .
            Client::STATUS_ACTIVE . ',' .
            Client::STATUS_INACTIVE
    ]);

    $client = Client::findOrFail($id);

    $client->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Status klien berhasil diperbarui');
}

    /**
     * Hapus klien
     */


    public function destroy($id)
{
    $client = Client::with('invoices')->findOrFail($id);
    
    // Tidak perlu menghapus file dari Google Drive lagi
    // Langsung hapus dari database
    $client->delete();

    return redirect()
        ->route('data-client.index')
        ->with('success', 'Data klien & invoice berhasil dihapus');
}

}
