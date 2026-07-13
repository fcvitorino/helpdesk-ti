<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link href="{{ asset('css/custom-pagination.css') }}" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VitDesk - Sistema de Chamados</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <style>
        .min-vh-100 {
            min-height: 100vh;
        }
        .card {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #0d6efd;
        }
    </style>
</head>
<body>
    
    @auth
        @include('layouts.navigation')
    @endauth
    
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>
    
    <!-- ========================================== -->
    <!-- JQUERY (PRIMEIRO) -->
    <!-- ========================================== -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- ========================================== -->
    <!-- BOOTSTRAP 5 JS BUNDLE -->
    <!-- ========================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ========================================== -->
    <!-- SELECT2 JS (DEPOIS DO JQUERY) -->
    <!-- ========================================== -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.js"></script>
    
    <!-- ========================================== -->
    <!-- SCRIPTS PERSONALIZADOS -->
    <!-- ========================================== -->
    @yield('scripts')
</body>
</html>