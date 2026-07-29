<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home - displays the main landing page
$routes->get('/', 'Home::index');

// Auth - show the login form
$routes->get('login', 'AuthController::login');
// Auth - process the login form submission
$routes->post('login', 'AuthController::loginProcess');
// Auth - show the registration form
$routes->get('register', 'AuthController::register');
// Auth - process the registration form submission
$routes->post('register', 'AuthController::registerProcess');
// Auth - log the user out and end the session
$routes->get('logout', 'AuthController::logout');
// Auth - test route for checking controller
$routes->get('test', 'AuthController::index');
// Auth - show the forgot password page
$routes->get('forgot-password', 'AuthController::forgotPassword');
// PIN recovery - verify the account email before a new PIN is created
$routes->get('forgot-pin', 'AuthController::forgotPin');
$routes->post('forgot-pin', 'AuthController::forgotPinProcess');

// Expenses - show the form to create a new expense
$routes->get('expenses/create', 'ExpenseController::create', ['filter' => 'auth']);
// Expenses - store a new expense to the database
$routes->post('expenses/store', 'ExpenseController::store', ['filter' => 'auth']);
// Expenses - display the list of all expenses
$routes->get('expenses/index', 'ExpenseController::index', ['filter' => 'auth']);
// Expenses - show the edit form for a specific expense by ID
$routes->get('expenses/edit/(:num)', 'ExpenseController::edit/$1', ['filter' => 'auth']);
// Expenses - process the update form submission for a specific expense by ID
$routes->post('expenses/update/(:num)', 'ExpenseController::update/$1', ['filter' => 'auth']);
// Expenses - delete a specific expense by ID
$routes->post('expenses/delete/(:num)', 'ExpenseController::delete/$1', ['filter' => 'auth']);

// Reports - display the reports overview page
$routes->get('reports/index', 'ReportController::index', ['filter' => 'auth']);

// Dashboard - displays the main dashboard page
$routes->get('index', 'DashboardController::index', ['filter' => 'auth']);

// Show the OTP verification page
$routes->get('verify', 'AuthController::verifyShow');
// Process the submitted OTP code (matches this view's form action)
$routes->post('otp/verify', 'AuthController::verifyOtp');
// Resend a new code (matches this view's resend button)
$routes->post('otp/resend', 'AuthController::resendOtp');

// PIN - created after email verification and used during subsequent sign-ins
$routes->get('pin/setup', 'AuthController::pinSetupShow');
$routes->post('pin/setup', 'AuthController::pinSetup');
$routes->get('pin/verify', 'AuthController::pinVerifyShow');
$routes->post('pin/verify', 'AuthController::pinVerify');
$routes->get('pin/reset', 'AuthController::pinResetShow');
$routes->post('pin/reset', 'AuthController::pinReset');

$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('users', 'AdminController::users');
    $routes->get('expenses', 'AdminController::expenses');
    $routes->post('make-admin/(:num)', 'AdminController::makeAdmin/$1');
    $routes->post('make-user/(:num)', 'AdminController::makeUser/$1');
});


$routes->get('check-session', 'AuthController::checkSession');
