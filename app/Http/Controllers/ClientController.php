<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Menampilkan form tambah client
     */
    public function create()
    {
        $categories = Client::getCategories();
        $statuses = Client::getStatuses();
        
        return view('add-clients.create', compact('categories', 'statuses'));
    }

    /**
     * Menyimpan client baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'nama_brand' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:clients,email',
            'address' => 'nullable|string',
            'category' => 'required|in:' . implode(',', Client::getCategories()),
            'join_date' => 'required|date',
            'status' => 'required|in:' . implode(',', array_keys(Client::getStatuses())),
            'notes' => 'nullable|string',
        ]);

        try {
            Client::create($validated);
            
            return redirect()->route('data-client.index')
                ->with('success', 'Client berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}