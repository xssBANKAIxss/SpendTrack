<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
  protected $table = 'expenses';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'user_id',
    'title',
    'amount',
    'category',
    'expense_date',
    'notes'
  ];

  protected $useTimestamps = true;
}
