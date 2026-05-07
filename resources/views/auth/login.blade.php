<x-guest-layout>
    <style>
        /* CSS Murni untuk Background Full Layar */
        .login-page-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #4a4a4a;
            z-index: 9999;
        }

        .login-card {
            background: white;
            box-sizing: border-box;
            padding: 1rem 1rem;
            border-radius: 24px;
            width: 100%;
            max-width: 340px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        .login-card img {
            width: 100px;
            height: 70px;
            margin-bottom: 10px;
            margin-top: 20px;
            /* spacing dari teks di bawahnya */
        }

        .login-card h1 {
            font-size: 1.25rem;
            margin-top: 0.5rem;
            margin-bottom: 0.1rem;
            color: #4b5563;
            /* gray-600 */
        }

        .login-card p {
            font-size: 0.85rem;
            color: #6b7280;
        }

        /* Input Styling */
        .input-group {
            position: relative;
            margin-bottom: 1rem;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 1.25rem;
            transform: translateY(-50%);
            color: #888888;
            width: 1.25rem;
            height: 1.25rem;
            pointer-events: none;
        }

        .custom-input {
            width: 100%;
            box-sizing: border-box;
            /* Prevent padding from expanding width */
            background-color: #ededed;
            border: 1px solid #ededed;
            border-radius: 10px;
            padding: 0.7rem 1.25rem 0.7rem 3.2rem; /* Atas dan bawah dibuat sama agar teks berada di tengah vertikal */
            font-size: 1rem;
            color: #4b5563;
            transition: all 0.2s;
        }

        .custom-input::placeholder {
            color: #888888;
        }

        .custom-input:focus {
            outline: none;
            border-color: #4ade80;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.15);
        }

        /* Tombol Hijau */
        .btn-container {
            display: flex;
            gap: 20px;
            margin-top: 1rem;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            justify-content: center;
        }

        .btn-green {
            flex: 1;
            background-color: #4ade80;
            color: white;
            padding: 0.6rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 4px 6px -1px rgba(74, 222, 128, 0.2);
        }

        .btn-green:hover {
            background-color: #22c55e;
        }

        /* Error Alert Styling */
        .error-alert {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #f87171;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .error-alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
    </style>

    <div class="login-page-wrapper">
        <div class="login-card">
            <div class="mb-4">
                <img src="{{ asset('images/logo_bps.png') }}" alt="Logo BPS" class="h-12 mx-auto">
            </div>

            <!-- Header Teks -->
            <div class="mb-2">
                <p class="text-base font-bold text-gray-500 uppercase leading-tight">
                    BADAN PUSAT STATISTIK<br>KOTA SUKABUMI
                </p>
                <h1 class="text-2xl font-black mt-1 leading-none text-gray-700 mb-0">ALPHA</h1>
                <p class="text-base text-gray-500 mt-1">Aplikasi Pelaporan Harian</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Pesan Error -->
                @if ($errors->any())
                    <div class="error-alert">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>Username/NIP atau Password yang Anda masukkan salah.</span>
                    </div>
                @endif

                <!-- Login ID (Email/NIP) -->
                <div class="input-group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="input-icon">
                        <path fill-rule="evenodd"
                            d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                            clip-rule="evenodd" />
                    </svg>
                    <input id="login_id" type="text" name="login_id" class="custom-input" placeholder="Username/NIP"
                        :value="old('login_id')" required autofocus />
                </div>

                <!-- Password -->
                <div class="input-group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="input-icon">
                        <path fill-rule="evenodd"
                            d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    <input id="password" type="password" name="password" class="custom-input" placeholder="Password"
                        required />
                </div>

                <!-- Tombol -->
                <div class="btn-container">
                    <button type="submit" name="role_login" value="admin" class="btn-green">
                        Login Admin
                    </button>
                    <button type="submit" name="role_login" value="pegawai" class="btn-green">
                        Login User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>