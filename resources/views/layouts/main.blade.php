<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $siteName ?? config('app.name', 'LenLab') }}</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    
    {{-- Dynamic CSS from settings --}}
    <style>
        {!! $dynamicCss ?? '' !!}
    </style>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    @stack('styles')
    
    <style>
        /* Reset & Base Styles */
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Figtree', sans-serif;
            line-height: 1.6;
        }
        
        /* Container */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }
        
        /* Mobile First - Header Styles */
        header {
            background: linear-gradient(135deg, #0a1a0a, #1a3a1a);
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        header .bg {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #22c55e;
        }
        
        /* Mobile Navigation - Hidden by default */
        .nav {
            display: none;
        }
        
        .nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 20px;
        }
        
        .nav a {
            text-decoration: none;
            color: #ffffff;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav a:hover {
            color: #22c55e;
        }
        
        /* Mobile User Cart */
        .user-cart {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .search-box {
            display: none; /* Hidden on mobile */
        }
        
        .search-box input {
            border: 1px solid #ddd;
            padding: 8px 12px;
            border-radius: 5px 0 0 5px;
            outline: none;
            width: 150px;
        }
        
        .search-box button {
            background: #22c55e;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }
        
        .user-profile, .cart-icon {
            position: relative;
            cursor: pointer;
            padding: 8px;
        }
        
        .user-profile i, .cart-icon i {
            font-size: 1.2rem;
            color: #ffffff;
        }
        
        .cart-count {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #22c55e;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }
        
        /* Mobile Banner */
        #banner {
            min-height: 400px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }
        
        #banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
        }
        
        .box-left {
            position: relative;
            z-index: 2;
            padding: 0 16px;
            text-align: center;
        }
        
        .box-left h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 16px;
        }
        
        .box-left p {
            font-size: 1rem;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        
        #buy-now {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            max-width: 200px;
        }
        
        #buy-now:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }
        
        /* Mobile Sections */
        section {
            padding: 40px 0;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .section-header h2 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 16px;
        }
        
        .view-all {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        /* Mobile Grids */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .product-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .product-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .product-image {
            width: 100%;
            height: 120px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 12px;
        }
        
        .product-name {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .view-product {
            background: #ff6b6b;
            color: white;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .view-product:hover {
            background: #ff5252;
            color: white;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .category-item {
            text-align: center;
            padding: 24px 16px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .category-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .category-item i {
            font-size: 2rem;
            color: #ff6b6b;
            margin-bottom: 12px;
        }
        
        .category-item h3 {
            font-size: 0.9rem;
            margin: 0;
            color: #333;
        }
        
        .reviews-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .review-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .review-content p {
            font-size: 0.9rem;
            margin-bottom: 12px;
            color: #666;
        }
        
        .stars {
            color: #ffc107;
            margin-bottom: 8px;
        }
        
        .review-author span {
            font-size: 0.8rem;
            color: #999;
        }
        
        #product-categories {
            background: #f8f9fa;
        }
        
        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: linear-gradient(135deg, #1a3a1a, #0f2a0f);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 5px;
            padding: 10px 0;
            min-width: 200px;
            display: none;
            z-index: 1000;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .dropdown-menu li {
            padding: 0;
        }
        
        .dropdown-menu a {
            display: block;
            padding: 8px 16px;
            font-size: 0.9rem;
            color: #ffffff;
        }
        
        .dropdown-menu a:hover {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        
        .user-dropdown {
            right: 0;
            left: auto;
        }
        
        /* Tablet Styles */
        @media (min-width: 768px) {
            .container {
                padding: 0 24px;
            }
            
            header .bg {
                padding: 15px 24px;
            }
            
            .logo {
                font-size: 1.6rem;
            }
            
            .nav {
                display: block;
            }
            
            .search-box {
                display: flex;
            }
            
            .user-cart {
                gap: 16px;
            }
            
            #banner {
                min-height: 500px;
            }
            
            .box-left {
                text-align: left;
                padding: 0 24px;
            }
            
            .box-left h2 {
                font-size: 2.5rem;
            }
            
            .box-left p {
                font-size: 1.1rem;
            }
            
            #buy-now {
                width: auto;
                padding: 15px 30px;
            }
            
            section {
                padding: 60px 0;
            }
            
            .section-header h2 {
                font-size: 2.2rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            
            .product-image {
                height: 150px;
            }
            
            .product-name {
                font-size: 1rem;
            }
            
            .categories-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            
            .category-item {
                padding: 32px 20px;
            }
            
            .category-item i {
                font-size: 2.5rem;
            }
            
            .category-item h3 {
                font-size: 1rem;
            }
            
            .reviews-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }
        
        /* Desktop Styles */
        @media (min-width: 1024px) {
            .container {
                padding: 0 32px;
            }
            
            header .bg {
                padding: 15px 32px;
            }
            
            .logo {
                font-size: 1.8rem;
            }
            
            .nav ul {
                gap: 30px;
            }
            
            .search-box input {
                width: 200px;
            }
            
            .user-cart {
                gap: 20px;
            }
            
            #banner {
                min-height: 600px;
            }
            
            .box-left {
                padding: 0 50px;
            }
            
            .box-left h2 {
                font-size: 3rem;
            }
            
            .box-left p {
                font-size: 1.2rem;
            }
            
            section {
                padding: 80px 0;
            }
            
            .section-header {
                margin-bottom: 50px;
            }
            
            .section-header h2 {
                font-size: 2.5rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
                margin-bottom: 50px;
            }
            
            .product-image {
                height: 180px;
            }
            
            .product-info {
                padding: 16px;
            }
            
            .product-name {
                font-size: 1.1rem;
            }
            
            .categories-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
                margin-bottom: 50px;
            }
            
            .category-item {
                padding: 40px 20px;
            }
            
            .category-item i {
                font-size: 3rem;
            }
            
            .category-item h3 {
                font-size: 1.1rem;
            }
            
            .reviews-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
            }
            
            .review-item {
                padding: 30px;
            }
        }
        
        /* Large Desktop */
        @media (min-width: 1440px) {
            .products-grid {
                grid-template-columns: repeat(5, 1fr);
            }
            
            .categories-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    @include('layouts.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.footer')
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>