<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $folder = Folder::all();
        $perPage = $request->input('per_page', 100);

        $search = $request->input('search');

        // Ambil data dan filter berdasarkan pencarian (jika ada)
        $device = Device::when($search, function ($query, $search) {
            return $query->where('nama_nopol', 'LIKE', "%{$search}%");
        })->paginate($perPage);

        // Format kolom expired_date menjadi Y-m-d
        $device->each(function ($item) {
            if ($item->tgl_install) {
                $item->tgl_install = Carbon::parse($item->tgl_install)->format('Y-m-d');
            }
        });
        return view('device', compact('device', 'folder', 'perPage'));
    }

    public function search(Request $request)
    {
        $folder = Folder::all();
        $perPage = $request->input('per_page', 100);

        $search = $request->input('search');

        // Ambil data dan filter berdasarkan pencarian
        $device = Device::when($search, function ($query, $search) {
            return $query->where('nama_nopol', 'LIKE', "%{$search}%")
                ->orWhere('imei', 'LIKE', "%{$search}%"); // Tambahkan kolom lain jika diperlukan
        })->paginate($perPage);

        // Format kolom expired_date menjadi Y-m-d
        $device->each(function ($item) {
            if ($item->tgl_install) {
                $item->tgl_install = Carbon::parse($item->tgl_install)->format('Y-m-d');
            }
        });

        return view('device', compact('device', 'folder', 'perPage'));
    }

    public function getDevicesByFolder($id)
    {
        $devices = Device::where('folder_id', $id)->get();

        if ($devices->isEmpty()) {
            return response()->json(['message' => 'No devices found for this folder'], 404);
        }

        return response()->json($devices, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'folder_id' => 'required',
            'nama_nopol' => 'required|unique:devices,nama_nopol',
            'imei' => 'required|numeric',
            'notlpn' => 'required|numeric',
            'tgl_install' => 'required|date',
        ]);

        $val_data = $request->all();
        Device::create($val_data);

        return redirect('/device')->with('success', 'New Device Created Successfully!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'folder_id' => 'required',
            'nama_nopol' => 'required|unique:devices,nama_nopol,' . $id . ',id',
            'imei' => 'required|numeric',
            'notlpn' => 'required|numeric',
            'tgl_install' => 'required|date',
        ]);

        $device = Device::findOrFail($id);
        $val_data = $request->all();
        $device->update($val_data);

        return response()->json(['success' => true, 'message' => 'Edit Device Successfully!']);
    }

    // Method destroy di FolderController
    public function destroy($id)
    {
        $device = Device::findOrFail($id); // Akan otomatis melempar 404 jika tidak ditemukan
        $device->delete();

        return response()->json(['success' => true, 'message' => 'Delete Device Successfully!']);
    }
}
