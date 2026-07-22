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

// Expenses - show the form to create a new expense
$routes->get('expenses/create', 'ExpenseController::create');
// Expenses - store a new expense to the database
$routes->post('expenses/store', 'ExpenseController::store');
// Expenses - display the list of all expenses
$routes->get('expenses/index', 'ExpenseController::index');
// Expenses - show the edit form for a specific expense by ID
$routes->get('expenses/edit/(:num)', 'ExpenseController::edit/$1');
// Expenses - process the update form submission for a specific expense by ID
$routes->post('expenses/update/(:num)', 'ExpenseController::update/$1');
// Expenses - delete a specific expense by ID
$routes->post('expenses/delete/(:num)', 'ExpenseController::delete/$1');

// Reports - display the reports overview page
$routes->get('reports/index', 'ReportController::index');

// Dashboard - displays the main dashboard page
$routes->get('index', 'DashboardController::index');

// Show the OTP verification page
$routes->get('verify', 'AuthController::verifyShow');
// Process the submitted OTP code (matches this view's form action)
$routes->post('otp/verify', 'AuthController::verifyOtp');
// Resend a new code (matches this view's resend button)
$routes->post('otp/resend', 'AuthController::resendOtp');

$routes->get('admin/dashboard', 'AdminController::dashboard');

$routes->get('admin/users', 'AdminController::users');

$routes->get('admin/expenses', 'AdminController::expenses');

$routes->post('admin/make-admin/(:num)', 'AdminController::makeAdmin/$1');
$routes->post('admin/make-user/(:num)', 'AdminController::makeUser/$1');
