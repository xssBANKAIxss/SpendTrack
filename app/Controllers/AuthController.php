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
    if ($throttler->check(md5($this->request->getIPAddress()), 5, MINUTE) === false) {
      return redirect()->to('/login')->with('error', 'Too many login attempts. Please wait a minute and try again.');
    }

    $model = new UserModel();
    $email = trim($this->request->getPost('email'));
    $password = trim($this->request->getPost('password'));

    $user = $model->where('email', $email)->first();

    if (!$user || !password_verify($password, $user['password'])) {
      return redirect()->to('/login')->with('error', 'Invalid email or password');
    }

    // Password correct — always send a fresh OTP before letting them in
    $this->sendOtpToUser($user['id'], $user['email']);
    session()->set('pending_user_id', $user['id']);

    return redirect()->to('/verify');
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

    $userId = $model->insert([
      'name'        => $firstName . ' ' . $lastName,
      'email'       => $this->request->getPost('email'),
      'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
      'is_verified' => 0,
      'role'        => 'user',
    ]);

    $this->sendOtpToUser($userId, $this->request->getPost('email'));

    session()->set('pending_user_id', $userId);

    return redirect()->to('/verify')->with('success', 'Account created. Check your email for the verification code.');
  }

  private function sendOtpToUser($userId, $email)
  {
    $otpModel = new \App\Models\OtpModel();

    $code = (string) random_int(100000, 999999);

    $otpModel->insert([
      'user_id'    => $userId,
      'otp_code'   => $code,
      'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
      'created_at' => date('Y-m-d H:i:s'),
    ]);

    $emailService = service('email');
    $emailService->setTo($email);
    $emailService->setSubject('Your SpendTrack verification code');
    $emailService->setMessage("Your verification code is: {$code}\n\nThis code expires in 5 minutes.");
    $emailService->send();
  }

  public function verifyOtp()
  {
    $userId = session()->get('pending_user_id');
    if (!$userId) {
      return redirect()->to('/login');
    }

    $otpModel = new \App\Models\OtpModel();
    $submittedCode = trim($this->request->getPost('otp_code'));

    $latestOtp = $otpModel->where('user_id', $userId)
      ->orderBy('id', 'DESC')
      ->first();

    if (!$latestOtp || $latestOtp['otp_code'] !== $submittedCode) {
      return redirect()->to('/verify')->with('error', 'Invalid code. Please try again.');
    }

    if (strtotime($latestOtp['expires_at']) < time()) {
      return redirect()->to('/verify')->with('error', 'Code expired. Please request a new one.');
    }

    $userModel = new UserModel();
    $userModel->update($userId, ['is_verified' => 1]);

    $user = $userModel->find($userId);

    session()->remove('pending_user_id');
    session()->regenerate();

    session()->set('user_id', $user['id']);
    session()->set('isLoggedIn', true);
    session()->set('name', $user['name']);
    session()->set('role', $user['role']);

    return redirect()->to(base_url('index'))->with('success', 'Email verified! Welcome.');
  }

  public function verifyShow()
  {
    $userId = session()->get('pending_user_id');
    if (!$userId) {
      return redirect()->to('/login');
    }

    $userModel = new UserModel();
    $user = $userModel->find($userId);

    return view('auth/otp', ['email' => $user['email']]);
  }
  public function resendOtp()
  {
    $userId = session()->get('pending_user_id');
    if (!$userId) {
      return redirect()->to('/login');
    }

    $userModel = new UserModel();
    $user = $userModel->find($userId);

    $this->sendOtpToUser($user['id'], $user['email']);

    return redirect()->to('/verify')->with('success', 'A new code has been sent to your email.');
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
