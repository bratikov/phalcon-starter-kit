<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class ClientMigration_1740743292128280.
 */
class ClientMigration_1740743292128280 extends Migration
{
	/**
	 * Define the table structure.
	 *
	 * @throws Exception
	 */
	public function morph(): void
	{
		$this->morphTable('client', [
			'columns' => [
				new Column(
					'id',
					[
						'type' => Column::TYPE_INTEGER,
						'notNull' => true,
						'autoIncrement' => true,
						'size' => 1,
						'first' => true,
					]
				),
				new Column(
					'username',
					[
						'type' => Column::TYPE_VARCHAR,
						'notNull' => true,
						'size' => 255,
						'after' => 'id',
					]
				),
				new Column(
					'password',
					[
						'type' => Column::TYPE_VARCHAR,
						'notNull' => true,
						'size' => 255,
						'after' => 'username',
					]
				),
				new Column(
					'role',
					[
						'type' => Column::TYPE_ENUM,
						'notNull' => true,
						'size' => "'owner','maintainer','user'",
						'after' => 'password',
					]
				),
				new Column(
					'created_at',
					[
						'type' => Column::TYPE_DATETIME,
						'default' => 'CURRENT_TIMESTAMP',
						'notNull' => true,
						'after' => 'role',
					]
				),
				new Column(
					'updated_at',
					[
						'type' => Column::TYPE_DATETIME,
						'default' => 'CURRENT_TIMESTAMP',
						'notNull' => true,
						'after' => 'created_at',
					]
				),
				new Column(
					'deleted_at',
					[
						'type' => Column::TYPE_DATETIME,
						'notNull' => false,
						'after' => 'updated_at',
					]
				),
			],
			'indexes' => [
				new Index('PRIMARY', ['id'], 'PRIMARY'),
				new Index('username', ['username'], 'UNIQUE'),
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
