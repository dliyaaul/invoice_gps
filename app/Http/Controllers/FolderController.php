<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folder = Folder::all();
        $folder->each(function ($item) {
            if ($item->expired_date) {
                $item->expired_date = Carbon::parse($item->expired_date)->format('Y-m-d');
            }
        });
        return view('folder', compact('folder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|unique:folders,login',
            'nama' => 'required|unique:folders,nama',
            'perusahaan' => 'required|max:100',
            'nohp' => 'required|numeric',
            'email' => 'required|email',
            'kota' => 'required',
            'alamat' => 'required|max:100',
            'expired_date' => 'required|date',
            'status' => 'string|in:active,inactive',
        ]);

        if (!$request->has('status')) {
            $request->merge(['status' => 'inactive']);
        }

        $val_data = $request->all();
        Folder::create($val_data);

        return redirect('/folder_device')->with('success', 'New User Created Successfully!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'login' => 'required|unique:folders,login,' . $id . ',id',
            'nama' => 'required|unique:folders,nama,' . $id . ',id',
            'perusahaan' => 'required|max:100',
            'nohp' => 'required|numeric',
            'email' => 'required|email',
            'kota' => 'required',
            'alamat' => 'required|max:100',
            'expired_date' => 'required|date',
            'status' => 'string|in:active,inactive',
        ]);

        if (!$request->has('status')) {
            $request->merge(['status' => 'inactive']);
        }

        $folder = Folder::findOrFail($id);
        $val_data = $request->all();
        $folder->update($val_data);

        return redirect('/folder_device')->with('success', 'Edit User Successfully!');
    }

    // Method destroy di FolderController
    public function destroy($id)
    {
        $folder = Folder::findOrFail($id); // Akan otomatis melempar 404 jika tidak ditemukan
        $folder->delete();

        return redirect()->route('folder_device.index')->with('success', 'User Deleted Successfully.');
    }
}
