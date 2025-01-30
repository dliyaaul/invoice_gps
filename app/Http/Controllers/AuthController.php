<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash as Hash;
use Illuminate\Support\Facades\Session as Session;

class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(): View
    {
        return view('auth.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration(): View
    {
        return view('auth.registration');
    }

    public function showVerifyEmailForm()
    {
        return view('forgot');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json(['success' => true, 'message' => 'Email verified. Please update your password.']);
        }

        return response()->json(['success' => false, 'message' => 'Email not found.']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            Session::flush();
            Auth::logout();

            return redirect('login')->with('status', 'Password updated successfully. Please log in again.');
        }

        return back()->withErrors(['email' => 'Failed to update password.']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request): RedirectResponse
    {
        $maxAttempts = 3; // Jumlah maksimal percobaan login
        $lockoutTime = 300; // Waktu lockout dalam detik (1 menit)

        // Validasi input
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        // Membaca input username dan password
        $credentials = $request->only('name', 'password');

        // Mengecek apakah sudah melebihi batas percobaan login
        $attempts = Session::get('login_attempts', 0);
        $lockoutTimeLeft = Session::get('lockout_time', 0) - time();

        if ($attempts >= $maxAttempts) {
            // Jika sudah melebihi percobaan, cek apakah lockout masih berlaku
            if ($lockoutTimeLeft > 0) {
                return redirect()->route('login')
                    ->withError('Terlalu banyak percobaan login gagal. Coba lagi dalam ' . ceil($lockoutTimeLeft / 60) . ' menit.');
            }
        }

        // Coba login dengan credentials
        if (Auth::attempt($credentials)) {
            // Jika login berhasil, reset percobaan login dan lockout time
            Session::forget('login_attempts');
            Session::forget('lockout_time');

            return redirect()->intended('dashboard')
                ->withSuccess('You have Successfully logged in');
        }

        // Jika login gagal, tambah percobaan login
        $attempts++;
        Session::put('login_attempts', $attempts);

        // Jika sudah mencapai batas percobaan, set lockout time
        if ($attempts >= $maxAttempts) {
            Session::put('lockout_time', time() + $lockoutTime); // Set lockout untuk waktu tertentu
        }

        return redirect()->route('login')
            ->withError('Opps! Ada Kesalahan Input Pada Username & Password');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $user = $this->create($data);

        Auth::login($user);

        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
