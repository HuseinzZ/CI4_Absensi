<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================================
// 1. HOME/DEFAULT ROUTE (Dialihkan ke Login)
// ===============================================
$routes->get('/', 'Auth::index');


// ===============================================
// 2. AUTHENTICATION ROUTES (Auth Controller)
// ===============================================

// Login Page (GET) dan Proses Login (POST)
// Menggunakan match(['get', 'post']) untuk memproses form login.
// Catatan: Jika Anda mengakses /auth, ini akan diarahkan ke Auth::index().
$routes->match(['get', 'post'], 'auth', 'Auth::index');

// Logout
$routes->get('auth/logout', 'Auth::logout');

// Access Blocked Page
$routes->get('auth/blocked', 'Auth::blocked');


// ===============================================
// 3. ADMIN AREA (Admin Controller)
// ===============================================
// Menggunakan Grouping untuk URI segment '/admin'
$routes->group('admin', function ($routes) {
    // Dashboard Admin: http://localhost:8080/admin
    $routes->get('/', 'Admin::index');

    // Rute Admin lainnya di sini...
});


// ===============================================
// 4. EMPLOYEE/USER PROFILE AREA
// ===============================================
// Asumsi Controller 'Profile' untuk Role ID 2
$routes->group('profile', function ($routes) {
    // Halaman utama Profile: http://localhost:8080/profile
    $routes->get('/', 'Profile::index');

    // Rute Profile lainnya di sini...
});
