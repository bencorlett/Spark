<?php
/**
 * Spark Fuel Package By Ben Corlett
 * 
 * Spark - Set your fuel on fire!
 * 
 * The Spark Fuel Package is an open-source
 * fuel package consisting of several 'widgets'
 * engineered to make developing
 * administration systems easier and quicker.
 * 
 * @package    Fuel
 * @subpackage Spark
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://www.github.com/bencorlett/spark
 */
namespace Fuel\Migrations;

class Create_Cron {
	
	public function up()
	{
		\DBUtil::create_table('cron', array(
			'id'					=> array(
				'type'					=> 'int',
				'constraint'			=> 11,
				'auto_increment'		=> true,
			),
			'slug'					=> array(
				'type'					=> 'varchar',
				'constraint'			=> 255,
				'null'					=> true,
			),
			'method'				=> array(
				'type'					=> 'varchar',
				'constraint'			=> 255,
				'null'					=> true,
			),
			'data'					=> array(
				'type'					=> 'text',
			),
			'scheduled_for'			=> array(
				'type'					=> 'datetime',
				'null'					=> true,
			),
			'executed_at'			=> array(
				'type'					=> 'datetime',
				'null'					=> true,
			),
			'attempts'				=> array(
				'type'					=> 'smallint',
				'constraint'			=> 2,
				'default'				=> 0,
			),
			'created_at'			=> array(
				'type'					=> 'datetime',
				'null'					=> true,
			),
			'updated_at'			=> array(
				'type'					=> 'datetime',
				'null'					=> true,
			),
			'completed'				=> array(
				'type'					=> 'tinyint',
				'constraint'			=> 1,
				'default'				=> 0,
			),
		), array('id'));
	}
	
	public function down()
	{
		\DBUtil::drop_table('cron');
	}
}