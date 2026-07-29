<?php

namespace App\Controllers;

use App\Models\OtpModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
  private const OTP_RECHECK_SECONDS = 604800; // 7 days

  public function forgotPassword()
  {
    return view('auth/forgot-password');
  }

  public function forgotPin()
  {
    return view('auth/forgot_pin');
  }

  public function forgotPinProcess()
  {
    $email = trim((string) $this->request->getPost('email'));
    $throttler = service('throttler');
    $throttleKey = 'forgot-pin-' . md5($this->request->getIPAddress());

    if ($throttler->check($throttleKey, 3, MINUTE) === false) {
      return redirect()->to('/forgot-pin')->with('error', 'Too many requests. Please wait a minute and try again.');
    }

    $user = (new UserModel())->where('email', $email)->first();

    // Use the same response whether or not the account exists.
    session()->remove('pending_user_id');
    session()->remove('pin_setup_allowed');
    session()->set('pending_auth_stage', 'pin_reset_otp');
    session()->set('pending_reset_email', $email);

    if ($user) {
      session()->set('pending_user_id', $user['id']);
      $this->sendOtpToUser($user['id'], $user['email']);
    }

    return redirect()->to('/verify')->with('success', 'If that email belongs to an account, a verification code has been sent.');
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
    $email = trim((string) $this->request->getPost('email'));
    $password = trim((string) $this->request->getPost('password'));
    $user = $model->where('email', $email)->first();

    if (!$user || !password_verify($password, $user['password'])) {
      return redirect()->to('/login')->with('error', 'Invalid email or password.');
    }

    session()->set('pending_user_id', $user['id']);

    if ($this->otpIsRequired($user)) {
      $this->sendOtpToUser($user['id'], $user['email']);
      session()->set('pending_auth_stage', 'otp');

      return redirect()->to('/verify')->with('success', 'A verification code has been sent to your email.');
    }

    if (empty($user['pin'])) {
      session()->set('pending_auth_stage', 'pin_setup');
      session()->set('pin_setup_allowed', true);

      return redirect()->to('/pin/setup');
    }

    session()->set('pending_auth_stage', 'pin');

    return redirect()->to('/pin/verify');
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
    $firstName = (string) $this->request->getPost('first_name');
    $lastName = (string) $this->request->getPost('last_name');
    $email = trim((string) $this->request->getPost('email'));

    $userId = $model->insert([
      'name'        => $firstName . ' ' . $lastName,
      'email'       => $email,
      'password'    => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
      'is_verified' => 0,
      'role'        => 'user',
    ]);

    $this->sendOtpToUser($userId, $email);
    session()->set('pending_user_id', $userId);
    session()->set('pending_auth_stage', 'otp');

    return redirect()->to('/verify')->with('success', 'Account created. Check your email for the verification code.');
  }

  private function sendOtpToUser($userId, string $email): void
  {
    $otpModel = new OtpModel();
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
    $authStage = session()->get('pending_auth_stage');
    if (!in_array($authStage, ['otp', 'pin_reset_otp'], true)) {
      return redirect()->to('/login');
    }

    if (!$userId) {
      return redirect()->to('/forgot-pin')->with('error', 'The code is invalid or expired. Please request a new one.');
    }

    $otpModel = new OtpModel();
    $submittedCode = trim((string) $this->request->getPost('otp_code'));

    if (!preg_match('/^\d{6}$/', $submittedCode)) {
      return redirect()->to('/verify')->with('error', 'Enter the 6-digit code from your email.');
    }

    $latestOtp = $otpModel->where('user_id', $userId)
      ->orderBy('id', 'DESC')
      ->first();

    if (!$latestOtp || !hash_equals((string) $latestOtp['otp_code'], $submittedCode)) {
      return redirect()->to('/verify')->with('error', 'Invalid code. Please try again.');
    }

    if (strtotime($latestOtp['expires_at']) < time()) {
      $otpModel->delete($latestOtp['id']);
      return redirect()->to('/verify')->with('error', 'Code expired. Please request a new one.');
    }

    $userModel = new UserModel();
    $userModel->update($userId, [
      'is_verified'     => 1,
      'otp_verified_at' => date('Y-m-d H:i:s'),
    ]);
    $otpModel->delete($latestOtp['id']);

    $user = $userModel->find($userId);

    if ($authStage === 'pin_reset_otp') {
      session()->set('pending_auth_stage', 'pin_reset');
      session()->set('pin_setup_allowed', true);

      return redirect()->to('/pin/reset');
    }

    if (empty($user['pin'])) {
      session()->set('pending_auth_stage', 'pin_setup');
      session()->set('pin_setup_allowed', true);

      return redirect()->to('/pin/setup');
    }

    session()->set('pending_auth_stage', 'pin');

    return redirect()->to('/pin/verify');
  }

  public function pinSetupShow()
  {
    $user = $this->pendingUser();

    if (!$user || session()->get('pending_auth_stage') !== 'pin_setup' || !session()->get('pin_setup_allowed')) {
      return redirect()->to('/login');
    }

    if (!empty($user['pin'])) {
      return redirect()->to('/pin/verify');
    }

    return view('auth/pin', [
      'mode' => 'setup',
      'name' => $user['name'],
    ]);
  }

  public function pinSetup()
  {
    $user = $this->pendingUser();

    if (!$user || session()->get('pending_auth_stage') !== 'pin_setup' || !session()->get('pin_setup_allowed')) {
      return redirect()->to('/login');
    }

    $pin = trim((string) $this->request->getPost('pin'));
    $confirmation = trim((string) $this->request->getPost('pin_confirmation'));

    if (!preg_match('/^\d{4}$/', $pin)) {
      return redirect()->to('/pin/setup')->with('error', 'Your PIN must be exactly 4 digits.');
    }

    if ($pin !== $confirmation) {
      return redirect()->to('/pin/setup')->with('error', 'The PIN entries do not match.');
    }

    (new UserModel())->update($user['id'], [
      'pin' => password_hash($pin, PASSWORD_DEFAULT),
    ]);

    return $this->completeLogin($user);
  }

  public function pinVerifyShow()
  {
    $user = $this->pendingUser();

    if (!$user || session()->get('pending_auth_stage') !== 'pin') {
      return redirect()->to('/login');
    }

    if (empty($user['pin'])) {
      return redirect()->to('/pin/setup');
    }

    return view('auth/pin', [
      'mode' => 'verify',
      'name' => $user['name'],
    ]);
  }

  public function pinVerify()
  {
    $user = $this->pendingUser();

    if (!$user || session()->get('pending_auth_stage') !== 'pin') {
      return redirect()->to('/login');
    }

    if (empty($user['pin'])) {
      return redirect()->to('/pin/setup');
    }

    $pin = trim((string) $this->request->getPost('pin'));

    if (!preg_match('/^\d{4}$/', $pin)) {
      return redirect()->to('/pin/verify')->with('error', 'Enter your 4-digit PIN.');
    }

    $throttler = service('throttler');
    $throttleKey = 'pin-' . $user['id'] . '-' . md5($this->request->getIPAddress());
    if ($throttler->check($throttleKey, 5, MINUTE) === false) {
      return redirect()->to('/pin/verify')->with('error', 'Too many PIN attempts. Please wait a minute and try again.');
    }

    if (!password_verify($pin, $user['pin'])) {
      return redirect()->to('/pin/verify')->with('error', 'Incorrect PIN. Please try again.');
    }

    return $this->completeLogin($user);
  }

  public function pinResetShow()
  {
    $user = $this->pendingUser();

    if (!$user || session()->get('pending_auth_stage') !== 'pin_reset' || !session()->get('pin_setup_allowed')) {
      return redirect()->to('/forgot-pin');
    }

    return view('auth/pin', [
      'mode' => 'reset',
      'name' => $user['name'],
    ]);
  }

  public function pinReset()
  {
    $user = $this->pendingUser();

    if (!$user || session()->get('pending_auth_stage') !== 'pin_reset' || !session()->get('pin_setup_allowed')) {
      return redirect()->to('/forgot-pin');
    }

    $pin = trim((string) $this->request->getPost('pin'));
    $confirmation = trim((string) $this->request->getPost('pin_confirmation'));

    if (!preg_match('/^\d{4}$/', $pin)) {
      return redirect()->to('/pin/reset')->with('error', 'Your PIN must be exactly 4 digits.');
    }

    if ($pin !== $confirmation) {
      return redirect()->to('/pin/reset')->with('error', 'The PIN entries do not match.');
    }

    (new UserModel())->update($user['id'], [
      'pin' => password_hash($pin, PASSWORD_DEFAULT),
    ]);

    return $this->completeLogin($user);
  }

  private function completeLogin(array $user)
  {
    session()->remove('pending_user_id');
    session()->remove('pending_auth_stage');
    session()->remove('pin_setup_allowed');
    session()->remove('pending_reset_email');
    session()->regenerate();

    session()->set('user_id', $user['id']);
    session()->set('isLoggedIn', true);
    session()->set('name', $user['name']);
    session()->set('role', $user['role']);
    session()->set('email', $user['email']);

    return redirect()->to(base_url('index'))->with('success', 'Signed in successfully.');
  }

  private function pendingUser(): ?array
  {
    $userId = session()->get('pending_user_id');

    if (!$userId) {
      return null;
    }

    return (new UserModel())->find($userId);
  }

  private function otpIsRequired(array $user): bool
  {
    if (empty($user['is_verified']) || empty($user['otp_verified_at'])) {
      return true;
    }

    $verifiedAt = strtotime($user['otp_verified_at']);

    return $verifiedAt === false || $verifiedAt <= time() - self::OTP_RECHECK_SECONDS;
  }

  public function verifyShow()
  {
    $userId = session()->get('pending_user_id');
    $authStage = session()->get('pending_auth_stage');
    if (!in_array($authStage, ['otp', 'pin_reset_otp'], true)) {
      return redirect()->to('/login');
    }

    if ($authStage === 'pin_reset_otp' && !$userId) {
      return view('auth/otp', ['email' => session()->get('pending_reset_email')]);
    }

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
    $authStage = session()->get('pending_auth_stage');
    if (!in_array($authStage, ['otp', 'pin_reset_otp'], true)) {
      return redirect()->to('/login');
    }

    if (!$userId) {
      return redirect()->to('/forgot-pin')->with('error', 'The code is invalid or expired. Please request a new one.');
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
  public function checkSession()
  {
    $userId = session()->get('user_id');

    if (!$userId) {
      return $this->response->setJSON(['valid' => false, 'message' => 'Please sign in again.']);
    }

    $userModel = new \App\Models\UserModel();
    $currentUser = $userModel->find($userId);

    if (!$currentUser) {
      return $this->response->setJSON(['valid' => false, 'message' => 'Your account no longer exists. Please sign in again.']);
    }

    if ($currentUser['role'] !== session()->get('role')) {
      $oldRole = session()->get('role');
      $newRole = $currentUser['role'];

      if ($oldRole === 'user' && $newRole === 'admin') {
        $message = 'You have been promoted to Admin. Please sign in again.';
      } elseif ($oldRole === 'admin' && $newRole === 'user') {
        $message = 'You have been demoted to a regular user. Please sign in again.';
      } else {
        $message = 'Your account role has changed. Please sign in again.';
      }

      return $this->response->setJSON(['valid' => false, 'message' => $message]);
    }

    return $this->response->setJSON(['valid' => true]);
  }
}
