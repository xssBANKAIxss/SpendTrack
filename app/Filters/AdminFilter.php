<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    $userId = session()->get('user_id');

    if (!$userId) {
      return redirect()->to('/login');
    }

    // Check the CURRENT role in the database, not the old one saved in the session
    $user = (new UserModel())->find($userId);

    if (!$user || $user['role'] !== 'admin') {
      // Keep the session in sync so the old role doesn't linger
      session()->set('role', $user['role'] ?? 'user');

      return redirect()->to('/index')->with('error', 'Administrator access is required.');
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // No response changes are needed.
  }
}
