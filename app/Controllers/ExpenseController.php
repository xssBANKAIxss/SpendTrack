<?php

namespace App\Controllers;

use App\Models\ExpenseModel;

class ExpenseController extends BaseController
{

  public function create()
  {
    return view('expenses/create');
  }

  public function store()
  {
    $rules = [
      'title'        => 'required',
      'amount'       => 'required|numeric',
      'category'     => 'required',
      'expense_date' => 'required|valid_date',
    ];

    if (!$this->validate($rules)) {
      return redirect()->to(base_url('expenses/create'))
        ->with('error', implode(' ', $this->validator->getErrors()));
    }

    $model = new ExpenseModel();

    $model->insert([
      'user_id'      => session()->get('user_id'),
      'title'        => $this->request->getPost('title'),
      'amount'       => $this->request->getPost('amount'),
      'category'     => $this->request->getPost('category'),
      'expense_date' => $this->request->getPost('expense_date'),
      'notes'        => $this->request->getPost('notes'),
    ]);

    return redirect()->to(base_url('expenses/create'))
      ->with('success', 'Expense added successfully.');
  }

  public function index()
  {
    $model = new ExpenseModel();
    $userId = session()->get('user_id');
    $data['expenses'] = $model
      ->where('user_id', $userId)
      ->orderBy('expense_date', 'DESC')
      ->findAll();

    // Latest expense amount
    $latest = $model
      ->where('user_id', $userId)
      ->orderBy('expense_date', 'DESC')
      ->first();
    $data['latestAmount'] = $latest['amount'] ?? 0;

    // Highest single expense
    $highest = $model
      ->where('user_id', $userId)
      ->orderBy('amount', 'DESC')
      ->first();
    $data['highestExpense'] = $highest['amount'] ?? 0;

    // Top Category
    $data['topCategory'] = (new ExpenseModel())
      ->select('category, COUNT(*) as total')
      ->where('user_id', $userId)
      ->groupBy('category')
      ->orderBy('total', 'DESC')
      ->first()['category'] ?? 'None';

    // Total number of records
    $data['totalRecords'] = $model
      ->where('user_id', $userId)
      ->countAllResults();

    return view('expenses/index', $data);
  }

  // added $id parameter to fetch the expense
  public function edit($id)
  {
    $model = new ExpenseModel();

    $expense = $model
      ->where('id', $id)
      ->where('user_id', session()->get('user_id'))
      ->first();

    if (!$expense) {
      return redirect()->to(base_url('expenses/index'))
        ->with('error', 'Expense not found or you do not have access.');
    }

    return view('expenses/edit', ['expense' => $expense]);
  }

  // ✅ NEW: update the expense
  public function update($id)
  {
    $model = new ExpenseModel();

    $expense = $model
      ->where('id', $id)
      ->where('user_id', session()->get('user_id'))
      ->first();

    if (!$expense) {
      return redirect()->to(base_url('expenses/index'))
        ->with('error', 'Expense not found or you do not have access.');
    }

    $rules = [
      'title'        => 'required',
      'amount'       => 'required|numeric',
      'category'     => 'required',
      'expense_date' => 'required|valid_date',
    ];

    if (!$this->validate($rules)) {
      return redirect()->to(base_url('expenses/edit/' . $id))
        ->with('error', implode(' ', $this->validator->getErrors()));
    }

    $model->update($id, [
      'title'        => $this->request->getPost('title'),
      'amount'       => $this->request->getPost('amount'),
      'category'     => $this->request->getPost('category'),
      'expense_date' => $this->request->getPost('expense_date'),
      'notes'        => $this->request->getPost('notes'),
    ]);

    return redirect()->to(base_url('expenses/index'))
      ->with('success', 'Expense updated.');
  }

  // delete the expense
  public function delete($id)
  {
    $model = new ExpenseModel();

    $expense = $model
      ->where('id', $id)
      ->where('user_id', session()->get('user_id'))
      ->first();

    if (!$expense) {
      return redirect()->to(base_url('expenses/index'))
        ->with('error', 'Expense not found or you do not have access.');
    }

    $model->delete($id);

    return redirect()->to(base_url('expenses/index'))
      ->with('success', 'Expense deleted.');
  }
}
