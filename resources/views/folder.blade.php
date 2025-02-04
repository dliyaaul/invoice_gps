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
        @if (session('success'))
            <div id="toast-success"
                class="absolute right-0 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 z-20 animate-slide-in"
                role="alert">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('success') }}</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div id="toast-warning"
                class="absolute right-0 flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 z-20 animate-slide-in"
                role="alert">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
                    </svg>
                    <span class="sr-only">Warning icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-warning" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif
        <div
            class="w-full bg-white p-2 flex items-center justify-between sticky top-0 z-10 transition duration-300 border-bottom">
            <div class="flex gap-2">
                <button type="button" data-modal-target="folder_create" data-modal-toggle="folder_create"
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
                    <form action="{{ route('folder.search') }}" method="GET">
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
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                            <option value="1000" {{ $perPage == 1000 ? 'selected' : '' }}>1000</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div id="folder_create" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-lg max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 h-[500px]">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-2 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                            Create New User
                        </h3>
                        <button type="button" id="close-modal-button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="folder_create">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5 overflow-y-auto h-[calc(100%-60px)]" action="/folder_device" method="POST"
                        enctype="multipart/form-data">
                        <div class="grid gap-4 mb-4 grid-cols-2">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="id" id="id">
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="nama"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Nama:</label>
                                <input type="text" name="nama" id="nama"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Nama" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="login"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Login:</label>
                                <input type="text" name="login" id="login"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter login" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="perusahaan"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Rental:</label>
                                <input type="text" name="perusahaan" id="perusahaan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Perusahaan/Rental" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="email"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Email:</label>
                                <input type="email" name="email" id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter email" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="nohp"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">No HP:</label>
                                <input type="number" name="nohp" id="nohp"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter No Handphone" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="kota"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Kota:</label>
                                <input type="text" name="kota" id="kota"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter kota" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="alamat"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Alamat:</label>
                                <input type="text" name="alamat" id="alamat"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Alamat" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="expired_date"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Expired
                                    Date:</label>
                                <input type="date" name="expired_date" id="expired_date"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Expired Date" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="status"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Status:</label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="status" id="status" onchange="toggleStatus()"
                                        class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                    <span id="status-text"
                                        class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        User Tidak Aktif
                                    </span>
                                </label>
                                <input type="hidden" name="status" id="status_value" value="inactive">
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
                            <span id="btn-submit">Add New User</span>
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
                            Update User
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
                                <label for="nama"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Nama:</label>
                                <input type="text" name="nama" id="edit-nama"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Nama" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="login"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Login:</label>
                                <input type="text" name="login" id="edit-login"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter login" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="perusahaan"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Rental:</label>
                                <input type="text" name="perusahaan" id="edit-perusahaan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Perusahaan/Rental" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="email"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Email:</label>
                                <input type="email" name="email" id="edit-email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter email" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="nohp"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">No HP:</label>
                                <input type="number" name="nohp" id="edit-nohp"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter No Handphone" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="kota"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Kota:</label>
                                <input type="text" name="kota" id="edit-kota"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter kota" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="alamat"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Alamat:</label>
                                <input type="text" name="alamat" id="edit-alamat"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Alamat" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="expired_date"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Expired
                                    Date:</label>
                                <input type="date" name="expired_date" id="edit-expiredDate"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Enter Expired Date" required="">
                            </div>
                            <div class="col-span-2 flex items-center gap-5">
                                <label for="status"
                                    class="block text-sm font-medium text-gray-900 dark:text-white w-1/4">Status:</label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="status" id="edit-status" onchange="toggleStatus()"
                                        class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                    <span id="status-text"
                                        class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        User Tidak Aktif
                                    </span>
                                </label>
                                <input type="hidden" name="status" id="edit-status-hidden" value="inactive">
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
                            <span id="btn-submit">Update User</span>
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
                            Status
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Login
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Perusahaan/Rental
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-2">
                            No Handphone
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Kota
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Alamat
                        </th>
                        <th scope="col" class="px-6 py-2">
                            Expired Date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($folder) > 0)
                        @foreach ($folder as $item)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <td class="px-4 py-2">
                                    <input type="radio" name="selectedFolder" class="select-folder"
                                        data-id="{{ $item->id }}" data-status="{{ $item->status }}"
                                        data-login="{{ $item->login }}" data-nama="{{ $item->nama }}"
                                        data-perusahaan="{{ $item->perusahaan }}" data-email="{{ $item->email }}"
                                        data-nohp="{{ $item->nohp }}" data-kota="{{ $item->kota }}"
                                        data-alamat="{{ $item->alamat }}" data-expiredDate="{{ $item->expired_date }}">
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->status }}
                                </td>
                                <th scope="row"
                                    class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $item->login }}
                                </th>
                                <td class="px-6 py-2">
                                    {{ $item->nama }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->perusahaan }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->email }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->nohp }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->kota }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->alamat }}
                                </td>
                                <td class="px-6 py-2">
                                    {{ $item->expired_date }}
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
                {{ $folder->appends(['per_page' => $perPage, 'search' => request('search')])->links() }}
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
            const editStatus = document.getElementById("edit-status");
            const editLogin = document.getElementById("edit-login");
            const editNama = document.getElementById("edit-nama");
            const editPerusahaan = document.getElementById("edit-perusahaan");
            const editEmail = document.getElementById("edit-email");
            const editKota = document.getElementById("edit-kota");
            const editAlamat = document.getElementById("edit-alamat");
            const editNoHp = document.getElementById("edit-nohp");
            const editExpiredDate = document.getElementById("edit-expiredDate");
            const closeModal = document.getElementById("closeModal");

            // Enable tombol Edit hanya jika data dipilih
            document.querySelectorAll(".select-user").forEach(radio => {
                radio.addEventListener("change", function() {
                    editButton.disabled = false;
                });
            });

            // Buka modal edit saat tombol Edit diklik
            editButton.addEventListener("click", function() {
                const selectedUser = document.querySelector(".select-folder:checked");
                if (selectedUser) {
                    editId.value = selectedUser.dataset.id;
                    editStatus.checked = selectedUser.dataset.status === "active";
                    toggleStatus();
                    editLogin.value = selectedUser.dataset.login;
                    editNama.value = selectedUser.dataset.nama;
                    editPerusahaan.value = selectedUser.dataset.perusahaan;
                    editEmail.value = selectedUser.dataset.email;
                    editKota.value = selectedUser.dataset.kota;
                    editAlamat.value = selectedUser.dataset.alamat;
                    editNoHp.value = selectedUser.dataset.nohp;
                    const expiredDate = selectedUser.dataset.expireddate;
                    if (expiredDate) {
                        const [date] = expiredDate.split(" "); // Ambil bagian tanggal
                        editExpiredDate.value = date; // Set nilai dengan format YYYY-MM-DD
                    } else {
                        editExpiredDate.value = ""; // Fallback jika tidak ada data
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

                fetch(`/folder_device/${id}`, {
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
            document.querySelectorAll(".select-folder").forEach(radio => {
                radio.addEventListener("change", function() {
                    deleteButton.disabled = false;
                });
            });

            // Aksi saat tombol Delete diklik
            deleteButton.addEventListener("click", function() {
                const selectedUser = document.querySelector(".select-folder:checked");
                if (selectedUser) {
                    const userId = selectedUser.dataset.id;
                    const nama = selectedUser.dataset.nama;

                    if (confirm(`Apakah Anda yakin ingin menghapus data ${nama}?`)) {
                        // Kirim permintaan DELETE menggunakan fetch
                        fetch(`/folder_device/${userId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute("content")
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert("Data berhasil dihapus");
                                    location.reload();
                                } else {
                                    alert("Terjadi kesalahan saat menghapus data");
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
    <script>
        function toggleStatus() {
            const checkbox = document.getElementById('edit-status');
            const statusText = document.getElementById('status-text');
            const statusValue = document.getElementById('edit-status');
            if (checkbox.checked) {
                statusText.textContent = 'User Aktif';
                statusValue.value = 'active';
            } else {
                statusText.textContent = 'User Tidak Aktif';
                statusValue.value = 'inactive';
            }
        }
    </script>
    <script>
        // Fungsi untuk menghilangkan toast setelah 5 detik
        function autoDismissToast() {
            const toasts = document.querySelectorAll('[id^="toast-"]'); // Pilih semua elemen toast
            toasts.forEach(toast => {
                setTimeout(() => {
                    if (toast) {
                        toast.classList.remove('animate-slide-in'); // Hapus animasi masuk
                        toast.classList.add('animate-slide-out'); // Tambahkan animasi keluar

                        // Tambahkan listener untuk menghapus elemen setelah animasi selesai
                        toast.addEventListener('animationend', () => {
                            toast.remove(); // Hapus elemen dari DOM
                        });
                    }
                }, 5000); // Hilangkan setelah 5 detik
            });
        }

        // Panggil fungsi setelah halaman selesai dimuat
        window.onload = autoDismissToast;

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk tombol "Close"
            document.querySelectorAll('[data-dismiss-target]').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-dismiss-target');
                    const toast = document.querySelector(targetId);
                    if (toast) {
                        toast.classList.remove('animate-slide-in');
                        toast.classList.add('animate-slide-out');
                        toast.addEventListener('animationend', () => {
                            toast.remove();
                        });
                    }
                });
            });
        });
    </script>
@endsection
