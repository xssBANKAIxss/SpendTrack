<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    if (!session()->get('user_id')) {
      return redirect()->to('/login');
    }

    if (session()->get('role') !== 'admin') {
      return redirect()->to('/index')->with('error', 'Administrator access is required.');
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // No response changes are needed.
  }
}
