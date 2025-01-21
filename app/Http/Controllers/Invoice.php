<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Folder;
use App\Models\Invoice as ModelsInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Invoice extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoice = ModelsInvoice::all();
        $search = $request->input('search');
        $folder =
            Folder::where('login', 'LIKE', "%{$search}%")->get();

        // Kirimkan devices sebagai array kosong
        return view('invoice_setting', compact('invoice', 'folder'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search'); // Ambil search dari input pencarian

        // Cari berdasarkan kolom login
        $folder = Folder::where('login', 'LIKE', "%{$search}%")->get();

        // Kembalikan data ke view
        return view('invoice_setting', compact('folder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
