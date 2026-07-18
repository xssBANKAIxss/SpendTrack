<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthFieldsToUsers extends Migration
{
  public function up()
  {
    $this->forge->addColumn('users', [
      'pin' => [
        'type'       => 'VARCHAR',
        'constraint' => 255,
        'null'       => true,
        'after'      => 'password',
      ],
      'otp_code' => [
        'type'       => 'VARCHAR',
        'constraint' => 10,
        'null'       => true,
        'after'      => 'pin',
      ],
      'otp_expires_at' => [
        'type'    => 'DATETIME',
        'null'    => true,
        'after'   => 'otp_code',
      ],
      'is_verified' => [
        'type'       => 'TINYINT',
        'constraint' => 1,
        'default'    => 0,
        'after'      => 'otp_expires_at',
      ],
      'otp_verified_at' => [
        'type'  => 'DATETIME',
        'null'  => true,
        'after' => 'is_verified',
      ],
    ]);
  }

  public function down()
  {
    $this->forge->dropColumn('users', [
      'pin',
      'otp_code',
      'otp_expires_at',
      'is_verified',
      'otp_verified_at',
    ]);
  }
}
