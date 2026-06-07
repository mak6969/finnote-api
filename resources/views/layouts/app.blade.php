<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinNote - Catatan Keuangan</title>
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3.2 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        h1, h2, h3, h4, h5, h6, .brand-logo {
            font-family: 'Outfit', sans-serif;
        }
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 50%, #311042 100%); 
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15);
        }
        .brand-logo {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(to right, #818cf8, #e879f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }
        .nav-link { 
            color: #94a3b8; 
            font-weight: 500;
            border-radius: 10px;
            margin-bottom: 6px;
            padding: 10px 15px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        .nav-link:hover { 
            background: rgba(255, 255, 255, 0.05); 
            color: #f8fafc; 
        }
        .nav-link.active { 
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); 
            color: #ffffff; 
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);
        }
        .card-income { border-left: 5px solid #28a745; }
        .card-expense { border-left: 5px solid #dc3545; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar text-white p-3" style="width: 250px;">
            <div class="brand-logo mb-4 mt-2 px-2">
                <i class="fas fa-wallet me-2"></i>FinNote
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('transactions') }}" class="nav-link {{ request()->is('transactions') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> Transaksi</a></li>
                <li class="nav-item"><a href="{{ route('categories') }}" class="nav-link {{ request()->is('categories') ? 'active' : '' }}"><i class="fas fa-tags"></i> Kategori</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <span class="navbar-brand">Selamat Datang, {{ Auth::user()->name ?? 'User' }}</span>
                    <div>
                        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>

            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>