<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Activity Manager' }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #000000;
            background-color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px 24px;
            border: 1px solid #000000;
            border-radius: 4px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        header h2 {
            margin: 0;
            font-size: 20px;
            color: #000000;
        }
        nav a {
            margin-left: 14px;
            text-decoration: none;
            color: #000000;
            font-weight: bold;
            font-size: 14px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 16px;
        }
        table, th, td {
            border: 1px solid #000000;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            vertical-align: middle;
            font-size: 13.5px;
            color: #000000;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #000000;
        }
        .alert {
            padding: 10px 14px;
            margin-bottom: 16px;
            border-radius: 3px;
            font-size: 13.5px;
            font-weight: 500;
        }
        .alert-success {
            background-color: #e8f5e9;
            color: #1b5e20;
            border: 1px solid #1b5e20;
        }
        .alert-danger {
            background-color: #ffebee;
            color: #b71c1c;
            border: 1px solid #b71c1c;
        }
        .form-group {
            margin-bottom: 14px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 13px;
            color: #000000;
        }
        .form-control {
            width: 100%;
            padding: 7px 9px;
            border: 1px solid #000000;
            border-radius: 3px;
            font-size: 13.5px;
            color: #000000;
            background-color: #ffffff;
        }
        .form-control:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.2);
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 3px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            line-height: 1.4;
            transition: opacity 0.15s ease;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .btn-primary {
            background-color: #0d6efd;
            color: #ffffff;
            border-color: #0b5ed7;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #ffffff;
            border-color: #565e64;
        }
        .btn-danger {
            background-color: #dc3545;
            color: #ffffff;
            border-color: #b02a37;
        }
        .btn-publish {
            background-color: #0d6efd;
            color: #ffffff;
            border-color: #0a58ca;
        }
        .btn-complete {
            background-color: #198754;
            color: #ffffff;
            border-color: #146c43;
        }
        .btn-sm {
            padding: 3px 8px;
            font-size: 12px;
            border-radius: 3px;
        }
        .badge {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #000000;
            color: #000000;
            background-color: #ffffff;
        }
        .badge-draft {
            background-color: #f4f4f4;
            color: #000000;
            border-color: #000000;
        }
        .badge-published {
            background-color: #ffffff;
            color: #000000;
            border: 1px dashed #000000;
        }
        .badge-completed {
            background-color: #000000;
            color: #ffffff;
            border-color: #000000;
        }

        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            margin: 0;
            gap: 2px;
            align-items: center;
        }
        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            padding: 0 6px;
            font-size: 13px;
            color: #000000;
            background-color: #ffffff;
            border: 1px solid #000000;
            border-radius: 3px;
            text-decoration: none;
        }
        .pagination li a:hover {
            background-color: #e6e6e6;
        }
        .pagination li.active span {
            background-color: #000000;
            color: #ffffff;
            border-color: #000000;
            font-weight: bold;
        }
        .pagination li.disabled span {
            color: #888888;
            background-color: #fafafa;
            border-color: #cccccc;
            cursor: not-allowed;
        }
        .pagination svg {
            width: 12px !important;
            height: 12px !important;
            max-width: 12px !important;
            max-height: 12px !important;
            fill: currentColor;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h2>Activity Manager</h2>
            <nav>
                <a href="{{ route('activities.index') }}">Daftar Kegiatan</a>
                <a href="{{ route('activities.create') }}">+ Tambah Kegiatan</a>
            </nav>
        </header>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>