<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 100);

        $search = $request->input('search');

        // Ambil data dan filter berdasarkan pencarian (jika ada)
        $folder = Folder::when($search, function ($query, $search) {
            return $query->where('login', 'LIKE', "%{$search}%");
        })->paginate($perPage);

        // Format kolom expired_date menjadi Y-m-d
        $folder->each(function ($item) {
            if ($item->expired_date) {
                $item->expired_date = Carbon::parse($item->expired_date)->format('Y-m-d');
            }
        });

        return view('folder', compact('folder', 'perPage'));
    }

    public function search(Request $request)
    {
        $perPage = $request->input('per_page', 100);

        $search = $request->input('search');

        // Ambil data dan filter berdasarkan pencarian
        $folder = Folder::when($search, function ($query, $search) {
            return $query->where('login', 'LIKE', "%{$search}%")
                ->orWhere('nama', 'LIKE', "%{$search}%"); // Tambahkan kolom lain jika diperlukan
        })->paginate($perPage);

        // Format kolom expired_date menjadi Y-m-d
        $folder->each(function ($item) {
            if ($item->expired_date) {
                $item->expired_date = Carbon::parse($item->expired_date)->format('Y-m-d');
            }
        });

        return view('folder', compact('folder', 'perPage'));
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

        return response()->json(['success' => true, 'message' => 'Edit User Successfully!']);
    }

    // Method destroy di FolderController
    public function destroy($id)
    {
        try {
            // Temukan kategori berdasarkan ID
            $folder = Folder::findOrFail($id);

            // Periksa apakah kategori memiliki relasi
            if ($folder->devices()->exists()) { // Ganti 'devices' dengan relasi aktual
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak bisa didelete, karena masih ada data yang terhubung.'
                ], 400); // Bad Request
            }

            // Lakukan penghapusan
            $folder->delete();

            return response()->json([
                'success' => true,
                'message' => 'User Deleted Successfully.'
            ], 200); // OK
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404); // Not Found
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the User.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }
}
