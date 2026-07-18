<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{

  public function forgotPassword()
  {
    return view('auth/forgot-password');
  }
  public function login()
  {
    return view('auth/login');
  }

  public function loginProcess()
  {

    $throttler = service('throttler');

    // Allow max 5 login attempts per minute, tracked by IP address
    if ($throttler->check(md5($this->request->getIPAddress()), 5, MINUTE) === false) {
      return redirect()->to('/login')->with('error', 'Too many login attempts. Please wait a minute and try again.');
    }

    $model = new UserModel();

    // trim() removes spaces from the start and end of the input
    $email = trim($this->request->getPost('email'));
    $password = trim($this->request->getPost('password'));

    $user = $model->where('email', $email)->first();

    if ($user && password_verify($password, $user['password'])) {
      session()->regenerate(); // gives the user a fresh session ID after login

      session()->set('user_id', $user['id']);
      session()->set('isLoggedIn', true);
      session()->set('name', $user['name']);

      return redirect()->to(base_url('index'));
    }

    return redirect()->to('/login')->with('error', 'Invalid email or password');
  }

  public function register()
  {
    return view('auth/register');
  }

  public function registerProcess()
  {
    $rules = [
      'first_name' => 'required|min_length[2]',
      'last_name'  => 'required|min_length[2]',
      'email'      => 'required|valid_email|is_unique[users.email]',
      'password'   => 'required|min_length[8]',
    ];

    if (!$this->validate($rules)) {
      return redirect()->to('/register')->withInput()->with('errors', $this->validator->getErrors());
    }

    $model = new UserModel();

    $firstName = $this->request->getPost('first_name');
    $lastName  = $this->request->getPost('last_name');

    $model->save([
      'name'     => $firstName . ' ' . $lastName,
      'email'    => $this->request->getPost('email'),
      'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
    ]);

    return redirect()->to('/login')->with('success', 'Account created. Please log in.');
  }

  public function logout()
  {
    session()->destroy();
    return redirect()->to('/login');
  }

  public function index()
  {
    if (!session()->get('isLoggedIn')) {
      return redirect()->to('/login');
    }

    return view('dashboard/index');
  }
}
