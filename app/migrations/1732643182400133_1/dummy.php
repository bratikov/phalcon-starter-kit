<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class DummyMigration_1732643182400133.
 */
class DummyMigration_1732643182400133 extends Migration
{
	/**
	 * Define the table structure.
	 *
	 * @throws Exception
	 */
	public function morph(): void
	{
		$this->morphTable('dummy', [
			'columns' => [
				new Column(
					'id',
					[
						'type' => Column::TYPE_INTEGER,
						'unsigned' => true,
						'notNull' => true,
						'autoIncrement' => true,
						'size' => 1,
						'first' => true,
					]
				),
				new Column(
					'name',
					[
						'type' => Column::TYPE_VARCHAR,
						'notNull' => true,
						'size' => 255,
						'after' => 'id',
					]
				),
				new Column(
					'updated_at',
					[
						'type' => Column::TYPE_DATETIME,
						'default' => 'CURRENT_TIMESTAMP',
						'notNull' => true,
						'after' => 'name',
					]
				),
				new Column(
					'created_at',
					[
						'type' => Column::TYPE_DATETIME,
						'default' => 'CURRENT_TIMESTAMP',
						'notNull' => true,
						'after' => 'updated_at',
					]
				),
			],
			'indexes' => [
				new Index('PRIMARY', ['id'], 'PRIMARY'),
			],
			'options' => [
				'TABLE_TYPE' => 'BASE TABLE',
				'AUTO_INCREMENT' => '',
				'ENGINE' => 'InnoDB',
				'TABLE_COLLATION' => 'utf8mb4_general_ci',
			],
		]);
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
	}
}
