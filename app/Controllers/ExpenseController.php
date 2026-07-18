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
    $model = new ExpenseModel();

    $model->insert([
      'user_id'      => session()->get('user_id'),
      'title'        => $this->request->getPost('title'),
      'amount'       => $this->request->getPost('amount'),
      'category'     => $this->request->getPost('category'),
      'expense_date' => $this->request->getPost('expense_date'),
      'notes'        => $this->request->getPost('notes'),
    ]);

    return redirect()->to(base_url('expenses/index'));
  }

  public function index()
  {
    $model = new ExpenseModel();
    $userId = session()->get('user_id');

    // Get all expenses for the table
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

    $data['expense'] = $model->find($id); // ✅ fetch the expense by id

    return view('expenses/edit', $data); // ✅ pass data to view
  }

  // ✅ NEW: update the expense
  public function update($id)
  {
    $model = new ExpenseModel();

    $model->update($id, [
      'title'        => $this->request->getPost('title'),
      'amount'       => $this->request->getPost('amount'),
      'category'     => $this->request->getPost('category'),
      'expense_date' => $this->request->getPost('expense_date'),
      'notes'        => $this->request->getPost('notes'),
    ]);

    return redirect()->to(base_url('expenses/index'));
  }

  // delete the expense
  public function delete($id)
  {
    $model = new ExpenseModel();

    $model->delete($id);

    return redirect()->to(base_url('expenses/index'));
  }
}
