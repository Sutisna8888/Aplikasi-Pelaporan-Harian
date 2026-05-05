<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_id' => ['required', 'string' ],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Ambil apa yang diketik user di form
        $loginId = $this->input('login_id');

        // 2. Cek apakah formatnya email? Jika iya jadikan 'email', jika tidak jadikan 'nip'
        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';

        // 3. Siapkan kunci untuk login
        $credentials = [
            $fieldType => $loginId,
            'password' => $this->input('password')
        ];

        // 4. Proses percobaannya
        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login_id' => trans('auth.failed'),
            ]);
        }
        // --- LOGIKA BARU: CEK ROLE TOMBOL ---
        $expectedRole = $this->input('role_login'); // Menangkap value dari tombol yang ditekan (admin/user)
        $actualRole = Auth::user()->role;           // Mengambil role asli dari database

        // Jika pegawai mencoba menekan tombol admin, atau sebaliknya
        if ($expectedRole !== $actualRole) {
            Auth::logout(); // Keluarkan lagi secara paksa
            
            throw ValidationException::withMessages([
                'login_id' => 'Gagal masuk. Pastikan Anda menekan tombol login yang sesuai dengan tipe akun Anda.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
