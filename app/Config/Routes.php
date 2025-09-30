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

// Login Page + Proses Login
$routes->match(['get', 'post'], 'auth', 'Auth::index');

// Logout
$routes->get('auth/logout', 'Auth::logout');

// Access Blocked Page
$routes->get('auth/blocked', 'Auth::blocked');

// ===============================================
// 3. ADMIN AREA (Admin Controller)
// ===============================================
// Grouping dengan prefix "admin"
$routes->group('admin', function ($routes) {
    $routes->get('', 'Admin::index'); // http://localhost:8080/admin
    // Rute admin lainnya...
});

// ===============================================
// 4. EMPLOYEE/USER PROFILE AREA (Profile Controller)
// ===============================================
$routes->group('profile', function ($routes) {
    $routes->get('', 'Profile::index'); // http://localhost:8080/profile
    // Rute profile lainnya...
});
