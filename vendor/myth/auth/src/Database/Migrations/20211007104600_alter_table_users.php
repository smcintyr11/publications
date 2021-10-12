<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_alter_table_users extends Migration
{
	public function up()
	{
		// add new identity info
		$fields = [
			'firstname'      => ['type' => 'VARCHAR', 'constraint' => 64, 'after' => 'username'],
			'lastname'       => ['type' => 'VARCHAR', 'constraint' => 64, 'after' => 'firstname'],
			'displayName'    => ['type' => 'VARCHAR', 'constraint' => 128, 'after' => 'lastname'],
		];
		$this->forge->addColumn('users', $fields);
	}

	public function down()
	{
		// drop new columns
		$this->forge->dropColumn('users', 'firstName');
		$this->forge->dropColumn('users', 'lastName');
		$this->forge->dropColumn('users', 'displayName');
	}
}
