<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Salim Beras Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #181e2b;
            padding: 24px;
            margin: 0;
        }

        .mat-login-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .mat-login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
        }

        .mat-login-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .mat-login-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #12171e;
            margin: 0;
            line-height: 1.2;
        }

        .mat-login-header p {
            font-size: 0.8125rem;
            color: #616e7c;
            margin: 4px 0 0;
        }

        .mat-brand-badge {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3366ff, #0043a9);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(51, 102, 255, 0.35);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.8125rem;
            color: #242c3a;
            margin-bottom: 6px;
        }

        .input-group-text {
            background-color: #f8f8f9;
            border: 1px solid #dbe1e8;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #616e7c;
            padding: 10px 14px;
        }

        .form-control {
            border: 1px solid #dbe1e8;
            border-left: none;
            border-radius: 0 8px 8px 0;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #3366ff;
            box-shadow: 0 0 0 3px rgba(51, 102, 255, 0.12);
        }

        .btn-mat-login {
            background: linear-gradient(135deg, #3366ff 0%, #0043a9 100%);
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #ffffff;
            width: 100%;
            box-shadow: 0 4px 12px rgba(51, 102, 255, 0.3);
            transition: all 0.2s ease;
        }

        .btn-mat-login:hover {
            opacity: 0.92;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(51, 102, 255, 0.4);
        }

        .mat-login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>

    <div class="mat-login-wrapper">
        <div class="mat-login-card">
            <div class="mat-login-header">
                <div>
                    <h2>Sign In</h2>
                    <p>Sistem Informasi Salim Beras</p>
                </div>
                <div class="mat-brand-badge">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success py-2 px-3 mb-3 border-0" style="border-radius:8px; font-size:0.8125rem; background-color:#e6f7f0; color:#007849;">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 mb-3 border-0" style="border-radius:8px; font-size:0.8125rem; background-color:#ffebeb; color:#a20e00;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username"
                               value="{{ old('username') }}" placeholder="Masukkan username" autofocus required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-mat-login">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Panel
                </button>
            </form>
        </div>

        <div class="mat-login-footer">
            &copy; {{ date('Y') }} Salim Beras — Salam, Kab. Magelang
        </div>
    </div>

</body>
</html>
