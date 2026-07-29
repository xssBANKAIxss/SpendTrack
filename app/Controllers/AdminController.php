<?php

namespace App\Controllers;

class AdminController extends BaseController
{
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

    $data['recentActivity'] = $expenseModel
      ->select('expenses.*, users.name as user_name')
      ->join('users', 'users.id = expenses.user_id')
      ->orderBy('expenses.created_at', 'DESC')
      ->limit(5)
      ->find();

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
    $userModel = new \App\Models\UserModel();
    $actingUser = $userModel->find(session()->get('user_id'));

    if (!$actingUser || $actingUser['email'] !== env('app.superAdminEmail')) {
      return redirect()->to('/admin/users')
        ->with('error', 'Only the designated super-admin can change user roles.');
    }

    if ((int) $id === (int) session()->get('user_id')) {
      return redirect()->to('/admin/users')
        ->with('error', 'You cannot change the role of your own account.');
    }

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
