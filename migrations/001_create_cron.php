<?php

namespace Fuel\Migrations;

class Create_Cron
{
	/**
	 * The table name
	 *
	 * @var string
	 */
	protected $table_name;

	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access  public
	 */
	public function __construct()
	{
		\Config::load('cron', true);
		$this->table_name = \Config::get('cron.table_name', 'cron');
	}
	
	public function up()
	{
		\DBUtil::create_table($this->table_name, array(
			'id' => array(
				'type'           => 'int',
				'constraint'     => 11,
				'auto_increment' => true,
			),
			'key' => array(
				'type'       => 'varchar',
				'constraint' => 255,
				'null'       => true,
			),
			'method' => array(
				'type'       => 'varchar',
				'constraint' => 255,
			),
			'data' => array(
				'type' => 'text',
				'null' => true,
			),
			'scheduled_for' => array(
				'type'       => 'int',
				'constraint' => 11,
			),
			'executed_at' => array(
				'type'       => 'int',
				'constraint' => 11,
				'null'       => true,
			),
			'attempts' => array(
				'type'       => 'smallint',
				'constraint' => 2,
				'default'    => 0,
			),
			'completed' => array(
				'type'       => 'tinyint',
				'constraint' => 1,
				'default'    => 0,
			),
			'created_at' => array(
				'type'       => 'int',
				'constraint' => 11,
			),
			'updated_at' => array(
				'type'       => 'int',
				'constraint' => 11,
			),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table($this->table_name);
	}
}