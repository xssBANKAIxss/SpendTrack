<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'user',
                'after'      => 'is_verified',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'role');
    }
}
