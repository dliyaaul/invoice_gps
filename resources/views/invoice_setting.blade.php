@extends('dashboard')

@section('content')
    <!-- Sidebar 2 -->
    <aside class="w-64 overflow-y-auto h-auto bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="sticky top-0 bg-white border-b border-gray-200 dark:border-gray-700">
            <div class="p-4">
                <div class="relative">
                    <form action="{{ route('folders.search') }}" method="GET">
                        <input type="text" placeholder="Search..." name="search"
                            class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <button type="submit" class="absolute right-3 top-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 dark:text-gray-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="px-4 py-4">
            @if (count($folder) > 0)
                <ul class="space-y-2">
                    <!-- Folder 1 -->
                    @foreach ($folder as $item)
                        <li id="folder_select">
                            <a href="#" data-id="{{ $item->id }}"
                                class="file-link flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">
                                <svg class="w-5 h-5 me-2 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7ZM8 16a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm1-5a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $item->login }}
                            </a>
                        </li>
                    @endforeach
                    {{-- </ul>
                    </details> --}}
                </ul>
            @else
                <li class="text-gray-500 dark:text-gray-400">No results found.</li>
            @endif
        </div>
    </aside>

    <!-- Content -->
    <div class="flex-1 bg-white dark:bg-gray-900 h-auto overflow-y-auto">
        <div class="p-2">
            <button type="button" id="export-csv"
                class="mb-3 text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2.5 text-center inline-flex items-center me-2">
                <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v9.293l-2-2a1 1 0 0 0-1.414 1.414l.293.293h-6.586a1 1 0 1 0 0 2h6.586l-.293.293A1 1 0 0 0 18 16.707l2-2V20a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z"
                        clip-rule="evenodd" />
                </svg>

                Export Invoice
            </button>

            <div class="bg-white dark:bg-gray-900">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="custom-search"
                        class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search for items">
                </div>
            </div>
            <table id="table-export"
                class="w-full max-h-screen text-left border-collapse border border-gray-300 dark:border-gray-700">
                <thead
                    class="table-auto top-0 bg-white dark:bg-gray-900 z-10 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th>
                            <span class="flex items-center">
                                <input id="select-all" type="checkbox" value=""
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                ID
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Nama - Nopol
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                IMEI
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                No GSM
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                        <th>
                            <span class="flex items-center">
                                Install Date
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                </svg>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hapus elemen pencarian secara manual
            const searchElement = document.querySelector('.datatable-search');
            if (searchElement) {
                searchElement.remove();
            }

            const table = new simpleDatatables.DataTable("#table-export", {
                fixedHeight: false,
                columns: [
                    // Kolom pertama (checkbox) tidak bisa di-sorting
                    {
                        select: 0,
                        sortable: false,
                    },
                ],
            });
            document.querySelectorAll('.file-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const folderId = this.getAttribute('data-id');
                    fetch(`/folders/${folderId}/devices`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                alert(data.message); // Tampilkan pesan jika tidak ada data
                                return;
                            }

                            const tableBody = document.querySelector('#table-export tbody');
                            tableBody.innerHTML =
                                ''; // Bersihkan tabel sebelum mengisi data baru

                            data.forEach(device => {
                                const row = `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td><input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"></td>
                            <td class="font-medium text-gray-900 dark:text-white">${device.id}</td></td>
                            <td class="font-medium text-gray-900 dark:text-white">${device.nama_nopol}</td>
                            <td>${device.imei}</td>
                            <td>${device.notlpn}</td>
                            <td>${device.tgl_install}</td>
                        </tr>
                    `;
                                tableBody.insertAdjacentHTML('beforeend', row);
                            });
                            table.update();
                        })
                        .catch(error => console.error('Error:', error));
                });
            });

            const searchInput = document.getElementById('custom-search');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                // Ambil semua baris tabel
                const rows = document.querySelectorAll('#table-export tbody tr');
                rows.forEach(row => {
                    const columns = row.querySelectorAll('td');
                    const searchData = Array.from(columns).map(col => col.textContent
                        .toLowerCase());

                    // Cek apakah ada kecocokan dengan input pencarian
                    const match = searchData.some(data => data.includes(searchTerm));

                    // Tampilkan atau sembunyikan baris sesuai dengan hasil pencarian
                    if (match) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <script>
        if (document.getElementById("table-export") && typeof simpleDatatables.DataTable !== 'undefined') {
            const table = new simpleDatatables.DataTable("#table-export", {
                fixedHeight: false,
                columns: [
                    // Kolom pertama (checkbox) tidak bisa di-sorting
                    {
                        select: 0,
                        sortable: false,
                    },
                ],
            });

            // Handle select-all checkbox
            document.getElementById("select-all").addEventListener("change", function() {
                const checkboxes = document.querySelectorAll("#table-export tbody input[type='checkbox']");
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
            });
            document.getElementById('export-csv').addEventListener('click', function() {
                const selectedData = [];
                const checkboxes = document.querySelectorAll('#table-export tbody input[type="checkbox"]:checked');

                checkboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const data = {
                        id: row.cells[1].textContent.trim(),
                        nama_nopol: row.cells[2].textContent.trim(),
                        imei: row.cells[3].textContent.trim(),
                        notlpn: row.cells[4].textContent.trim(),
                        tgl_install: row.cells[5].textContent.trim(),
                    };
                    selectedData.push(data);
                });

                if (selectedData.length === 0) {
                    alert('Pilih setidaknya satu item untuk diekspor!');
                    return;
                }

                // Buat form virtual untuk mengirim data ke Laravel
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/invoice_preview'; // Endpoint Laravel

                // Tambahkan CSRF token (wajib di Laravel)
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Tambahkan data sebagai input hidden
                selectedData.forEach((item, index) => {
                    Object.keys(item).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `data[${index}][${key}]`;
                        input.value = item[key];
                        form.appendChild(input);
                    });
                });

                document.body.appendChild(form);
                form.submit();
            });
        }
    </script>

    {{-- <script>
        if (document.getElementById("table-export") && typeof simpleDatatables.DataTable !== 'undefined') {
            const table = new simpleDatatables.DataTable("#table-export", {
                searchable: true,
                fixedHeight: true,
                columns: [
                    // Kolom pertama (checkbox) tidak bisa di-sorting
                    {
                        select: 0,
                        sortable: false,
                        searchable: false
                    },
                ],
            });

            // Handle select-all checkbox
            document.getElementById("select-all").addEventListener("change", function() {
                const checkboxes = document.querySelectorAll("#table-export tbody input[type='checkbox']");
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
            });

            // Button untuk mengirim data yang dipilih
            document.getElementById("export-csv").addEventListener("click", function() {
                const selectedRows = [];
                const checkboxes = document.querySelectorAll("#table-export tbody input[type='checkbox']:checked");

                checkboxes.forEach((checkbox) => {
                    const row = checkbox.closest("tr");
                    const cells = row.querySelectorAll("td");
                    const rowData = {
                        nama_nopol: cells[2]?.textContent.trim(),
                        imei: cells[3]?.textContent.trim(),
                        install_date: cells[4]?.textContent.trim(),
                        harga: cells[5]?.textContent.trim(),
                        qty: cells[6]?.textContent.trim(),
                        jumlah: cells[7]?.textContent.trim(),
                        keterangan: cells[8]?.textContent.trim(),
                    };
                    selectedRows.push(rowData);
                });

                console.log("Selected Rows:", selectedRows);

                if (selectedRows.length > 0) {
                    fetch("/export-preview", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    "content"),
                            },
                            body: JSON.stringify({
                                data: selectedRows
                            }),
                        })
                        .then((response) => response.blob())
                        .then((blob) => {
                            const link = document.createElement("a");
                            link.href = URL.createObjectURL(blob);
                            link.download = "preview_invoice.xlsx";
                            link.click();
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                        });
                } else {
                    alert("Pilih setidaknya satu baris untuk diexport.");
                }
            });
        }
    </script> --}}
@endsection
