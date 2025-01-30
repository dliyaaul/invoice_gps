<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Geagps Invoice</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon_geagps.jpg') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full p-4 px-5 py-5">
                    <h1 class="text-center text-xl font-bold">Verify Email</h1>
                    <form id="verify-email-form" class="mt-5 space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <button type="submit"
                                class="w-full px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Verify
                                Email</button>
                        </div>
                    </form>

                    <form id="update-password-form" style="display: none;" class="mt-5 space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" id="password" name="password" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <button type="submit"
                                class="w-full px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:bg-green-700">Update
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('build/assets/app.js') }}"></script>

    <script>
        document.getElementById('verify-email-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;

            axios.post('/verify-email', {
                    email
                })
                .then(response => {
                    if (response.data.success) {
                        alert(response.data.message);
                        document.getElementById('verify-email-form').style.display = 'none';
                        document.getElementById('update-password-form').style.display = 'block';
                    } else {
                        alert(response.data.message);
                    }
                })
                .catch(error => console.error(error));
        });

        document.getElementById('update-password-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            axios.post('/update-password', {
                    email,
                    password,
                    password_confirmation
                })
                .then(response => {
                    if (response.data.success) {
                        alert(response.data.message);
                        window.location.reload();
                    } else {
                        alert(response.data.message);
                    }
                })
                .catch(error => console.error(error));
        });
    </script>
</body>

</html>
