<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\OtpService;

helper('auth');

class OtpController extends BaseController
{
  public function show()
  {
    $userId = session()->get('pending_user_id');
    if (!$userId) {
      return redirect()->to('/login');
    }

    $user = (new UserModel())->find($userId);
    return view('auth/otp', ['email' => $user['email']]);
  }

  public function verify()
  {
    $userId = session()->get('pending_user_id');
    if (!$userId) {
      return redirect()->to('/login');
    }

    $model = new UserModel();
    $user  = $model->find($userId);

    $submittedCode = trim($this->request->getPost('otp_code'));
    $otpService    = new OtpService();

    if (!$otpService->verifyOtp($user, $submittedCode)) {
      return redirect()->to('/otp')->with('error', 'Invalid or expired code. Please try again.');
    }

    $model->update($user['id'], [
      'otp_verified_at' => date('Y-m-d H:i:s'),
      'is_verified'     => 1,
      'otp_code'        => null,
      'otp_expires_at'  => null,
    ]);

    if (empty($user['pin'])) {
      return redirect()->to('/pin/setup');
    }

    return complete_login($user);
  }

  public function resend()
  {
    $userId = session()->get('pending_user_id');
    if (!$userId) {
      return redirect()->to('/login');
    }

    $user = (new UserModel())->find($userId);
    (new OtpService())->sendOtp($user);

    return redirect()->to('/otp')->with('success', 'A new code has been sent to your email.');
  }
}
