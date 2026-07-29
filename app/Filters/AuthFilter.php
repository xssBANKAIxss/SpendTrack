<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    $userId = session()->get('user_id');

    if (!$userId) {
      return redirect()->to('/login');
    }

    $userModel = new \App\Models\UserModel();
    $currentUser = $userModel->find($userId);

    if (!$currentUser) {
      session()->remove(['user_id', 'isLoggedIn', 'name', 'role', 'email']);
      return redirect()->to('/login')->with('error', 'Your account no longer exists. Please sign in again.');
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

      session()->remove(['user_id', 'isLoggedIn', 'name', 'role', 'email']);
      return redirect()->to('/login')->with('error', $message);
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // do nothing
  }
}
