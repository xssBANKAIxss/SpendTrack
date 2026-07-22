<?php

namespace App\Controllers;

class AdminController extends BaseController
{
  public function __construct()
  {
    if (session()->get('role') !== 'admin') {
      exit('Access Denied');
    }
  }
  public function dashboard()
  {
    $userModel = new \App\Models\UserModel();
    $expenseModel = new \App\Models\ExpenseModel();

    $data['totalUsers'] = $userModel->countAllResults();

    $data['totalAdmins'] = $userModel
      ->where('role', 'admin')
      ->countAllResults();

    $data['totalExpenses'] = $expenseModel->countAllResults();

    $data['totalCategories'] = $expenseModel
      ->select('category')
      ->groupBy('category')
      ->countAllResults();

    return view('admin/dashboard', $data);
  }
  public function users()
  {
    $userModel = new \App\Models\UserModel();

    $data['users'] = $userModel->findAll();

    return view('admin/users', $data);
  }
  public function makeAdmin($id)
  {
    return $this->changeRole($id, 'admin', 'User promoted to Admin.');
  }
  public function expenses()
  {
    $db = \Config\Database::connect();

    $data['expenses'] = $db->table('expenses')
      ->select('expenses.*, users.name')
      ->join('users', 'users.id = expenses.user_id')
      ->orderBy('expense_date', 'DESC')
      ->get()
      ->getResultArray();

    return view('admin/expenses', $data);
  }
  public function makeUser($id)
  {
    return $this->changeRole($id, 'user', 'User demoted to User.');
  }

  private function changeRole($id, string $role, string $message)
  {
    if ((int) $id === (int) session()->get('user_id')) {
      return redirect()->to('/admin/users')
        ->with('error', 'You cannot change the role of your own account.');
    }

    $userModel = new \App\Models\UserModel();
    $user = $userModel->find($id);

    if (!$user) {
      return redirect()->to('/admin/users')
        ->with('error', 'The selected user no longer exists.');
    }

    $userModel->update($id, [
      'role' => $role
    ]);

    return redirect()->to('/admin/users')
      ->with('success', $message);
  }
}
