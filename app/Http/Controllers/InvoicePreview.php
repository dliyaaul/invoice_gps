<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Folder;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Invoice;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class InvoicePreview extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('invoice_preview', [
            'devices' => [], // Kirim array kosong
            'namaPemilik' => null,
            'username' => null,
            'invoiceNumber' => null,
        ]);
    }

    public function preview(Request $request)
    {
        $selectedData = $request->input('data');

        if (!$selectedData) {
            return redirect()->route('invoice_setting.index')->with('error', 'Data tidak ditemukan atau kosong.');
        }

        // Ambil data terkait dengan device dan invoice berdasarkan data yang dikirimkan
        $devices = collect($selectedData)->map(function ($data) {
            // Ambil data terkait dengan device dan invoice berdasarkan ID atau data lainnya
            $device = Device::where('nama_nopol', $data['nama_nopol'])->first();
            $invoice = Invoice::where('device_id', $device->id)->first(); // atau sesuai relasi Anda
            $folder = Folder::where('id', $device->folder_id)->first();

            // Gabungkan data dari device dan invoice
            return [
                'id' => $data['id'],
                'nama_nopol' => $data['nama_nopol'],
                'imei' => $data['imei'],
                'notlpn' => $data['notlpn'],
                'tgl_install' => $data['tgl_install'],
                'qty' => $invoice ? $invoice->qty : 0, // Ambil qty dari invoice
                'harga' => $invoice ? $invoice->harga : 0, // Ambil harga dari invoice
                'nama' => $folder ? $folder->nama : 'Tidak diketahui',
                'login' => $folder ? $folder->login : 'Tidak diketahui',
            ];
        });

        $namaPemilik = $devices->first()['nama'] ?? 'Tidak diketahui';
        $username = $devices->first()['login'] ?? 'Tidak diketahui';


        $invoiceId = Invoice::max('id') + 1;
        $month = date('m'); // Bulan sekarang
        $year = substr(date('Y'), 2, 2); // Dua angka terakhir dari tahun
        $invoiceNumber = $month . '0' . $invoiceId . $year; // Format nomor invoice

        return view('invoice_preview', compact('devices', 'invoiceId', 'invoiceNumber', 'namaPemilik', 'username'));
    }

    public function saveInvoice(Request $request)
    {
        try {
            // Validasi data yang dikirim
            $validated = $request->validate([
                'nama_pemilik' => 'required|string',
                'no_invoice' => 'required|string',
                'username' => 'required|string',
                'jatuh_tempo' => 'required|date',
                'harga' => 'required|numeric',
                'qty' => 'required|numeric',
                'jumlah' => 'required|numeric',
                'devices' => 'required|string',
            ]);

            Carbon::setLocale('id');

            $devices = json_decode($validated['devices'], true);

            // Validasi tambahan untuk devices
            if (!is_array($devices)) {
                throw new \Exception('Invalid devices format');
            }

            $invoice = new Invoice();
            $invoice->nama_pemilik = $validated['nama_pemilik'];
            $invoice->no_invoice = $validated['no_invoice'];
            $invoice->username = $validated['username'];
            $invoice->jatuh_tempo = $validated['jatuh_tempo'];
            $invoice->harga = $validated['harga'];
            $invoice->qty = $validated['qty'];
            $invoice->jumlah = $validated['jumlah'];
            $invoice->keterangan = "Tidak Ada";


            $invoice->device_id = $devices[0]['id'];
            $invoice->save();

            // Load template Excel
            $templatePath = storage_path('app/templates/invoice_template.xlsx');
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            $jatuhTempoFormatted = Carbon::parse($validated['jatuh_tempo'])->format('d-m-Y');
            $formattedDate = 'Surabaya, ' . Carbon::now()->translatedFormat('d F Y');
            $formattedMonthYear = Carbon::now()->translatedFormat('F Y');

            $monthRoman = Carbon::now()->format('m'); // Ambil bulan dalam format angka (01-12)
            $monthRoman = $this->convertToRoman((int) $monthRoman); // Konversi angka ke romawi
            $yearNow = Carbon::now()->year;


            $invoiceText = "Bersama ini kami sampaikan invoice untuk paket data perangkat GPS Tracker dengan server Pelacakan dan Manajemen Kendaraan GeaGPS periode bulan $formattedMonthYear, sebagai berikut :";

            $invoiceCode = "/Geagps-ABN/$monthRoman/$yearNow";


            // Isi data ke template
            $sheet->setCellValue('A9', $validated['nama_pemilik']);
            $sheet->setCellValue('E7', $validated['no_invoice']);
            $sheet->setCellValue('F9', $validated['username']);
            $sheet->setCellValue('F10', $jatuhTempoFormatted);
            $sheet->setCellValue('A6', $formattedDate);
            $sheet->setCellValue('A12', $invoiceText);
            $sheet->setCellValue('F7', $invoiceCode);

            $startRow = 15;
            foreach ($devices as $index => $device) {
                $currentRow = $startRow + $index;

                $formattedInstallDate = Carbon::parse($device['tgl_install'])->format('d-m-Y');

                $sheet->setCellValue("A{$currentRow}", $device['nama_nopol']);
                $sheet->setCellValue("B{$currentRow}", $device['imei']);
                $sheet->setCellValue("C{$currentRow}", $formattedInstallDate);
                $sheet->setCellValue("D{$currentRow}", $validated['harga']);
                $sheet->setCellValue("E{$currentRow}", $validated['qty']);
                $sheet->setCellValue("F{$currentRow}", $validated['jumlah']);
            }

            // Simpan file
            $fileName = 'filled_invoice.xlsx';
            $writer = new Xlsx($spreadsheet);

            // Kirim file ke output
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->send();

            return redirect()->route('invoice_setting.index')->with('success', 'Invoice berhasil disimpan dan diunduh.');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function convertToRoman($num)
    {
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1,
        ];
        $result = '';
        foreach ($map as $roman => $int) {
            while ($num >= $int) {
                $result .= $roman;
                $num -= $int;
            }
        }
        return $result;
    }

    public function downloadInvoice()
    {
        try {
            $filePath = storage_path('app/public/filled_invoice.xlsx');
            return response()->download($filePath, 'invoice.xlsx');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
