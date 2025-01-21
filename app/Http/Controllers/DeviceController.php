<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $device = Device::all();
        $folder = Folder::all();
        $device->each(function ($item) {
            if ($item->tgl_install) {
                $item->tgl_install = Carbon::parse($item->tgl_install)->format('Y-m-d');
            }
        });
        return view('device', compact('device', 'folder'));
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
            'nama_nopol' => 'required|unique:device,nama_nopol,' . $id . ',id',
            'imei' => 'required|numeric',
            'notlpn' => 'required|numeric',
            'tgl_install' => 'required|date',
        ]);

        $device = Device::findOrFail($id);
        $val_data = $request->all();
        $device->update($val_data);

        return redirect('/device')->with('success', 'Edit Device Successfully!');
    }

    // Method destroy di FolderController
    public function destroy($id)
    {
        $device = Device::findOrFail($id); // Akan otomatis melempar 404 jika tidak ditemukan
        $device->delete();

        return redirect()->route('device.index')->with('success', 'Device Deleted Successfully.');
    }
}
