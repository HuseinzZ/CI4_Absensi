<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================================
// 1. HOME/DEFAULT ROUTE
// ===============================================
$routes->get('/', 'Auth::index');

// ===============================================
// 2. AUTHENTICATION (Auth Controller)
// ===============================================
$routes->match(['get', 'post'], 'auth', 'Auth::index');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/blocked', 'Auth::blocked');

// ===============================================
// 3. ADMIN AREA (Admin Controller)
// ===============================================
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Admin::index'); // Dashboard Admin
});

// ===============================================
// 4. MASTER AREA (Master Controller)
// ===============================================
$routes->group('master', ['filter' => 'auth'], static function ($routes) {

    // Default Route: /master → Master::index (List Employee)
    $routes->get('/', 'Master::index');

    // -------------------------
    // EMPLOYEE MANAGEMENT
    // -------------------------
    $routes->get('employee', 'Master::index');
    $routes->match(['get', 'post'], 'a_employee', 'Master::a_employee');
    $routes->match(['get', 'post'], 'e_employee/(:num)', 'Master::e_employee/$1');
    $routes->get('d_employee/(:num)', 'Master::d_employee/$1');

    // -------------------------
    // POSITION MANAGEMENT (ID: CHAR/STRING)
    // -------------------------
    $routes->get('position', 'Master::position');
    $routes->match(['get', 'post'], 'a_position', 'Master::a_position');
    $routes->match(['get', 'post'], 'e_position/(:alpha)', 'Master::e_position/$1');
    $routes->get('d_position/(:alpha)', 'Master::d_position/$1');

    // -------------------------
    // USER ACCOUNT MANAGEMENT (ID: Username/String)
    // -------------------------
    $routes->get('users', 'Master::users');
    $routes->match(['get', 'post'], 'a_users/(:num)/(:any)', 'Master::a_users/$1/$2');
    $routes->match(['get', 'post'], 'e_users/(:any)', 'Master::e_users/$1');
    $routes->get('d_users/(:any)', 'Master::d_users/$1');

    // -------------------------
    // ATTENDANCE MANAGEMENT (ID: INT)
    // -------------------------
    $routes->get('attendance', 'Master::attendance');
    $routes->match(['get', 'post'], 'a_attendance', 'Master::a_attendance');
    $routes->match(['get', 'post'], 'e_attendance/(:any)', 'Master::e_attendance/$1');
    $routes->get('d_attendance(:any)', 'Master::d_attendance/$1');
});

// ===============================================
// 5. PROFILE AREA (Profile Controller)
// ===============================================
$routes->group('profile', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Profile::index');
});
