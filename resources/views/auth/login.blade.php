<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - FinNote</title>
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3.2 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            --glass-bg: rgba(255, 255, 255, 0.04);
            --glass-border: rgba(255, 255, 255, 0.08);
            --primary-glow: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --primary-glow-hover: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            overflow-x: hidden;
            position: relative;
        }

        /* Decorative background blobs */
        .blob {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            opacity: 0.4;
            pointer-events: none;
        }
        .blob-1 {
            background: #6366f1;
            top: -10%;
            left: -10%;
        }
        .blob-2 {
            background: #d946ef;
            bottom: -10%;
            right: -10%;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        h1, h2, h3, h4, .brand-logo {
            font-family: 'Outfit', sans-serif;
        }

        .card-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .brand-logo {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(to right, #818cf8, #e879f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .form-label-custom {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .input-group-custom {
            position: relative;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .input-group-custom:focus-within {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.25);
            background: rgba(15, 23, 42, 0.8);
        }

        .input-group-custom i {
            padding: 14px 18px;
            color: var(--text-muted);
            font-size: 1.1rem;
            min-width: 55px;
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .input-group-custom input {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 14px 16px;
            font-size: 0.95rem;
            width: 100%;
            outline: none;
        }

        .input-group-custom input::placeholder {
            color: #64748b;
        }

        .btn-premium {
            background: var(--primary-glow);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.25);
        }

        .btn-premium:hover {
            background: var(--primary-glow-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(168, 85, 247, 0.4);
            color: #ffffff;
        }

        .btn-premium:active {
            transform: translateY(0);
        }

        .auth-link {
            color: #c084fc;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-link:hover {
            color: #e879f9;
            text-decoration: underline;
        }

        /* Micro-animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body>
    <!-- Background blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-10">
                <div class="card-glass p-4 p-md-5 animate-fade-in">
                    <div class="text-center mb-4">
                        <div class="brand-logo mb-2">
                            <i class="fas fa-wallet me-2"></i>FinNote
                        </div>
                        <h4 class="fw-semibold text-white mb-1">Selamat Datang Kembali</h4>
                        <p class="text-muted small">Masuk untuk mengelola catatan keuangan Anda</p>
                    </div>

                    <!-- Alert message -->
                    @if(session('error'))
                        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger small p-3 rounded-3 mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-20 text-success small p-3 rounded-3 mb-4">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="/login">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label-custom">Alamat Email</label>
                            <div class="input-group-custom">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" placeholder="contoh@domain.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <label class="form-label-custom">Password</label>
                            <div class="input-group-custom">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium w-100 mb-3">
                            Masuk Sekarang <i class="fas fa-sign-in-alt ms-2"></i>
                        </button>

                        <div class="text-center mt-3">
                            <span class="text-muted small">Belum punya akun? </span>
                            <a href="{{ route('register') }}" class="auth-link small">Daftar gratis di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>