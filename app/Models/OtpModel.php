<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
  protected $table            = 'otps';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;

  protected $allowedFields = [
    'user_id',
    'otp_code',
    'expires_at',
    'created_at',
  ];

  protected $useTimestamps = false;
}
