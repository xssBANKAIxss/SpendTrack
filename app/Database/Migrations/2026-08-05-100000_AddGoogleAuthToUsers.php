<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleAuthToUsers extends Migration
{
  public function up()
  {
    $this->forge->addColumn('users', [
      'google_id' => [
        'type'       => 'VARCHAR',
        'constraint' => 255,
        'null'       => true,
        'unique'     => true,
        'after'      => 'password',
      ],
      'avatar_url' => [
        'type'       => 'VARCHAR',
        'constraint' => 500,
        'null'       => true,
        'after'      => 'google_id',
      ],
    ]);

    // Google users don't have a password, so this column can no longer
    // be required for every row.
    $this->forge->modifyColumn('users', [
      'password' => [
        'name'       => 'password',
        'type'       => 'VARCHAR',
        'constraint' => 255,
        'null'       => true,
      ],
    ]);
  }

  public function down()
  {
    $this->forge->dropColumn('users', ['google_id', 'avatar_url']);

    $this->forge->modifyColumn('users', [
      'password' => [
        'name'       => 'password',
        'type'       => 'VARCHAR',
        'constraint' => 255,
        'null'       => false,
      ],
    ]);
  }
}
