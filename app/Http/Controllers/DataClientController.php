<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataClientController extends Controller
{
    /**
     * Menampilkan halaman data klien
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Query data klien dengan pencarian
        $clients = Client::when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('telepon', 'like', '%' . $search . '%')
                      ->orWhere('perusahaan', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('data-client.index', compact('clients', 'search'));
    }

    /**
     * Menampilkan detail klien
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('data-client.show-client', compact('client'));
    }

    /**
     * Menampilkan form edit klien
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('data-client.edit-client', compact('client'));
    }

    /**
     * Update data klien
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|string|max:20',
            'perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());

        return redirect()->route('data-client.index')
            ->with('success', 'Data klien berhasil diperbarui');
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:active,inactive'
    ]);

    $client = Client::findOrFail($id);
    $client->status = $request->status;
    $client->save();

    return redirect()->back()->with('success', 'Status klien berhasil diperbarui.');
}


    /**
     * Hapus data klien
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('data-client.index')
            ->with('success', 'Data klien berhasil dihapus');
    }
}