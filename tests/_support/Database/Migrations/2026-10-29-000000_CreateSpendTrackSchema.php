<?php

namespace Tests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpendTrackSchema extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'              => ['type' => 'INTEGER', 'auto_increment' => true],
            'name'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 254],
            'password'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'pin'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_verified'     => ['type' => 'INTEGER', 'default' => 0],
            'otp_verified_at' => ['type' => 'DATETIME', 'null' => true],
            'role'            => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'user'],
            'google_id'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'avatar_url'      => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');

        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id'      => ['type' => 'INTEGER'],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'amount'       => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'category'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'expense_date' => ['type' => 'DATE'],
            'notes'        => ['type' => 'TEXT', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('expenses');

        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id'    => ['type' => 'INTEGER'],
            'otp_code'   => ['type' => 'VARCHAR', 'constraint' => 10],
            'expires_at' => ['type' => 'DATETIME'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('otps');
    }

    public function down(): void
    {
        $this->forge->dropTable('otps', true);
        $this->forge->dropTable('expenses', true);
        $this->forge->dropTable('users', true);
    }
}
