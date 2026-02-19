<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication' }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            max-width: 460px;
            width: 100%;
            border-radius: 18px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .auth-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: auto;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .btn-auth {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            padding: 10px;
            font-weight: bold;
        }

        .btn-auth:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

<div class="card auth-card p-4 bg-white">

    <!-- Icon -->
    <div class="auth-icon mb-3">
        <i class="bi {{ $icon ?? 'bi-person-circle' }}"></i>
    </div>

    <!-- Title -->
    <h4 class="text-center fw-bold">{{ $heading ?? 'Auth Page' }}</h4>
    <p class="text-center text-muted mb-4">{{ $subheading ?? '' }}</p>

    <!-- Page Content -->
     @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
