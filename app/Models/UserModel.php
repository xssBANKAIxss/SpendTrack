<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
  protected $table = 'users';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'name',
    'email',
    'password',
    'pin',
    'is_verified',
    'otp_verified_at',
    'role',
    'google_id',
    'avatar_url',
  ];

  protected $useTimestamps = true;

  public function getUserByEmail($email)
  {
    // Email addresses are case-insensitive. Normalising here keeps lookups
    // reliable when a user changes browser/account login details.
    return $this->where('LOWER(email)', strtolower(trim((string) $email)))->first();
  }
}
