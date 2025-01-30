@extends('dashboard')

@section('css')
    <style>
        /* Animasi slide ke kiri */
        @keyframes slideInLeft {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutLeft {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }

        /* Tambahkan kelas animasi */
        .animate-slide-in {
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .animate-slide-out {
            animation: slideOutLeft 0.5s ease-out forwards;
        }
    </style>
@endsection

@section('content')
    <div class="flex-1 bg-white dark:bg-gray-900 overflow-y-auto h-auto">
        <div
            class="w-full bg-white p-2 flex items-center justify-between sticky top-0 z-10 transition duration-300 border-bottom">
            <div class="flex gap-2">
                <button type="button" data-modal-target="device_create" data-modal-toggle="device_create"
                    class="text-dark hover:bg-gray-200 font-medium rounded-lg text-base px-2 py-1 text-center inline-flex items-center me-2">
                    <svg class="w-5 h-5 me-2 text-blue-600 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                    </svg>
                    Create
                </button>
                <button type="button" id="editButton"
                    class="text-dark hover:bg-gray-200 font-medium rounded-lg text-base px-2 py-1 text-center inline-flex items-center me-2">
                    <svg class="w-5 h-5 me-2 text-yellow-400 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                    </svg>
                    Edit
                </button>
                <button type="button" id="deleteButton"
                    class="text-dark hover:bg-gray-200 font-medium rounded-lg text-base px-2 py-1 text-center inline-flex items-center me-2">
                    <svg class="w-5 h-5 me-2 text-red-500 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                    </svg>
                    Delete
                </button>
                <div class="relative">
                    <form action="{{ route('device.search') }}" method="GET">
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

                <div class="flex items-center justify-between">
                    <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                        {{-- Dropdown untuk jumlah entri per halaman --}}
                        <select name="per_page" id="per_page"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            onchange="this.form.submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div id="device_create" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-lg max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 h-[500px]">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-2 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                            Create New Device
                        </h3>
                        <button type="button" id="close-modal-button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="device_create">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5 overflow-y-auto h-[calc(100%-60px)]" action="/device" method="POST"
                        enctype="multipart/form-data">
                        <div class="grid gap-4 mb-4 grid-cols-2">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="id" id="id">
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="folder_id"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">User:</label>
                                <select
                                    class="folder_id bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    name="folder_id" id="folder_id" aria-label="Default select example" required>
                                    <option value="" @readonly(true)>Enter Select User</option>
                                    @foreach ($folder as $item)
                                        <option value="{{ $item->id }}">{{ $item->login }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="nama_nopol"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Nopol:</label>
                                <input type="text" name="nama_nopol" id="nama_nopol"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Nopol" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="imei"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">IMEI:</label>
                                <input type="number" name="imei" id="imei"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter IMEI" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="notlpn"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">No GSM:</label>
                                <input type="number" name="notlpn" id="notlpn"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter No GSM" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="tgl_install"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Tgl Install
                                </label>
                                <input type="date" name="tgl_install" id="tgl_install"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Tgl Install" required="">
                            </div>
                        </div>
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span id="btn-submit">Add New Device</span>
                        </button>
                        <button type="reset"
                            class="text-white inline-flex items-center bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                            <svg class="me-1 -ms-1 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14" />
                            </svg>
                            Reset
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="editModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-lg max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 h-[500px]">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-2 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                            Update Device
                        </h3>
                        <button type="button" id="closeModal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5 overflow-y-auto h-[calc(100%-60px)]" action=""
                        enctype="multipart/form-data" id="editForm">
                        <div class="grid gap-4 mb-4 grid-cols-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit-id">
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="folder_id"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">User:</label>
                                <select
                                    class="folder_id bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    name="folder_id" id="edit-user" aria-label="Default select example" required>
                                    <option value="" @readonly(true)>Enter Select User</option>
                                    @foreach ($folder as $data)
                                        <option value="{{ $data->id }}">{{ $data->login }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="nopol"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Nama
                                    Nopol:</label>
                                <input type="text" name="nama_nopol" id="edit-nopol"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Nopol" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="imei"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">IMEI:</label>
                                <input type="number" name="imei" id="edit-imei"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter IMEI" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="notlpn"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">No GSM:</label>
                                <input type="number" name="notlpn" id="edit-notlpn"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter No GSM" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="tgl_install"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Tgl
                                    Install:</label>
                                <input type="date" name="tgl_install" id="edit-tglInstall"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Tgl Install" required="">
                            </div>
                        </div>
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span id="btn-submit">Update Device</span>
                        </button>
                        <button type="reset"
                            class="text-white inline-flex items-center bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                            <svg class="me-1 -ms-1 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14" />
                            </svg>
                            Reset
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg m-2 border-collapse border border-gray-300">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-2">
                            #
                        </th>
                        <th scope="col" class="px-6 py-2">
                            User
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Nama Nopol
                        </th>
                        <th scope="col" class="px-6 py-2">
                            IMEI
                        </th>
                        <th scope="col" class="px-6 py-2">
                            No GSM
                        </th>
                        <th scope="col" class="px-6 py-2">
                            TGL Install
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($device) > 0)
                        @foreach ($device as $item)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <td class="px-4 py-2">
                                    <input type="radio" name="selectedDevice" class="select-device"
                                        data-id="{{ $item->id }}" data-user="{{ $item->folder_id }}"
                                        data-nopol="{{ $item->nama_nopol }}" data-imei="{{ $item->imei }}"
                                        data-notlpn="{{ $item->notlpn }}" data-tgl_install="{{ $item->tgl_install }}">
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->folder->login }}
                                </td>
                                <th scope="row"
                                    class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $item->nama_nopol }}
                                </th>
                                <td class="px-6 py-2">
                                    {{ $item->imei }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->notlpn }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->tgl_install }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="px-6 py-2 text-center">Data Tidak Ditemukan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="m-2">
                {{ $device->appends(['per_page' => $perPage, 'search' => request('search')])->links() }}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dataTableElement = document.getElementById("search-table");

            if (dataTableElement && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#search-table", {
                    searchable: true,
                    sortable: true,
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButton = document.getElementById("editButton");
            const editModal = document.getElementById("editModal");
            const editForm = document.getElementById("editForm");
            const editId = document.getElementById("edit-id");
            const editUser = document.getElementById("edit-user");
            const editNopol = document.getElementById("edit-nopol");
            const editImei = document.getElementById("edit-imei");
            const editNotlpn = document.getElementById("edit-notlpn");
            const editTglInstall = document.getElementById("edit-tglInstall");
            const closeModal = document.getElementById("closeModal");

            // Enable tombol Edit hanya jika data dipilih
            document.querySelectorAll(".select-device").forEach(radio => {
                radio.addEventListener("change", function() {
                    editButton.disabled = false;
                });
            });

            // Buka modal edit saat tombol Edit diklik
            editButton.addEventListener("click", function() {
                const selectedDevice = document.querySelector(".select-device:checked");
                if (selectedDevice) {
                    editId.value = selectedDevice.dataset.id;
                    editUser.value = selectedDevice.dataset.user;
                    editNopol.value = selectedDevice.dataset.nopol;
                    editImei.value = selectedDevice.dataset.imei;
                    editNotlpn.value = selectedDevice.dataset.notlpn;
                    const tglInstall = selectedDevice.dataset.tgl_install;
                    if (tglInstall) {
                        const formattedDate = tglInstall.split(" ")[0]; // Ambil bagian tanggal
                        editTglInstall.value = formattedDate; // Set nilai dengan format YYYY-MM-DD
                    } else {
                        editTglInstall.value = ""; // Fallback jika tidak ada data
                    }
                    editModal.style.display = "block";
                } else {
                    alert("Pilih data terlebih dahulu!");
                }
            });

            // Tutup modal
            closeModal.addEventListener("click", function() {
                editModal.style.display = "none";
            });

            // Kirim form edit menggunakan AJAX
            editForm.addEventListener("submit", function(e) {
                e.preventDefault();
                const id = editId.value;
                const formData = new FormData(editForm);

                fetch(`/device/${id}`, {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json(); // Parsing JSON
                    })
                    .then(data => {
                        if (data.success) {
                            alert("Data berhasil diperbarui");
                            location.reload();
                        } else {
                            alert("Terjadi kesalahan");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButton = document.getElementById("deleteButton");

            // Enable tombol Delete hanya jika data dipilih
            document.querySelectorAll(".select-device").forEach(radio => {
                radio.addEventListener("change", function() {
                    deleteButton.disabled = false;
                });
            });

            // Aksi saat tombol Delete diklik
            deleteButton.addEventListener("click", function() {
                const selectedDevice = document.querySelector(".select-device:checked");
                if (selectedDevice) {
                    const id = selectedDevice.dataset.id;
                    const nopol = selectedDevice.dataset.nopol;

                    if (confirm(`Apakah Anda yakin ingin menghapus data ${nopol}?`)) {
                        // Kirim permintaan DELETE menggunakan fetch
                        fetch(`/device/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute("content")
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert("Data berhasil diperbarui");
                                    location.reload();
                                } else {
                                    alert("Terjadi kesalahan");
                                }
                            })
                            .catch(error => console.error("Error:", error));
                    }
                } else {
                    alert("Pilih data terlebih dahulu!");
                }
            });
        });
    </script>
@endsection
