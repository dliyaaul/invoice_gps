@extends('dashboard')

@section('content')
    <div class="max-w-8xl p-4 overflow-x-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl">
            <!-- Kolom Kiri: Invoice Preview -->
            <div class="col-span-2 bg-white shadow-md rounded-md border border-gray-300 p-4">
                <h1 class="mb-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white p-2">Invoice Preview</h1>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 rounded-s-md">ID</th>
                            <th scope="col" class="px-6 py-3">Nama Nopol</th>
                            <th scope="col" class="px-6 py-3">IMEI</th>
                            <th scope="col" class="px-6 py-3">No GSM</th>
                            <th scope="col" class="px-6 py-3">Tgl Install</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devices as $data)
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $data['id'] }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $data['nama_nopol'] }}
                                </th>
                                <td class="px-6 py-3">{{ $data['imei'] }}</td>
                                <td class="px-6 py-3">{{ $data['notlpn'] }}</td>
                                <td class="px-6 py-3">{{ $data['tgl_install'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Kolom Kanan: Form -->
            <div class="bg-white shadow-md rounded-md p-6 border border-gray-300">
                <h2 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Form Input</h2>
                <form action="/save-invoice" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="devices" value="{{ json_encode($devices) }}">
                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-700">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" id="user"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="{{ $namaPemilik }}" required readonly>
                    </div>
                    <div>
                        <label for="no_invoice" class="block text-sm font-medium text-gray-700">Nomor Invoice</label>
                        <input type="text" name="no_invoice" id="invoice-number"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="{{ $invoiceNumber }}" required readonly>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="{{ $username }}" required readonly>
                    </div>

                    <div>
                        <label for="jatuh_tempo" class="block text-sm font-medium text-gray-700">Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo" id="jatuh_tempo"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Masukkan jatuh tempo" required>
                    </div>

                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" name="harga" id="harga"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            oninput="calculateTotal()" required>
                    </div>

                    <div>
                        <label for="qty" class="block text-sm font-medium text-gray-700">Qty</label>
                        <input type="number" name="qty" id="qty"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            oninput="calculateTotal()" required>
                    </div>

                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="0" required>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Invoice
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Fungsi untuk menghitung total berdasarkan harga dan qty
        function calculateTotal() {
            const harga = document.getElementById("harga").value;
            const qty = document.getElementById("qty").value;
            const jumlah = document.getElementById("jumlah");

            if (harga && qty) {
                jumlah.value = harga * qty;
            }

            if (harga && qty == 0) {
                jumlah.value = 0;
            }
        }

        // Mendapatkan Nama Pemilik dan Username
        document.addEventListener("DOMContentLoaded", function() {
            // Nama Pemilik diambil dari data login
            // const userName = ""; // Mengambil username yang telah di-assign
            // document.getElementById("user").value = userName;

            // Nomor Invoice dihasilkan secara otomatis
            const invoiceNumber = "{{ $invoiceNumber }}"; // Format bulan + 0 + ID + 2 digit tahun
            document.getElementById("invoice-number").value = invoiceNumber;
        });
    </script>
@endsection
