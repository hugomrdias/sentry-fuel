<?php
/**
 * Part of the Sentry package for FuelPHP.
 *
 * @package    Sentry
 * @version    1.0
 * @author     Cartalyst LLC
 * @license    MIT License
 * @copyright  2011 Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Fuel\Migrations;

\Package::load('sentry');

class Install_Sentry_Auth {

	public function up()
	{
		\Config::load('sentry', true);

		\DBUtil::create_table(\Config::get('sentry.table.users'), array(
			'id'                  => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'username'            => array('constraint' => 50, 'type' => 'varchar'),
			'email'               => array('constraint' => 50, 'type' => 'varchar'),
			'password'            => array('constraint' => 81, 'type' => 'varchar'),
			'password_reset_hash' => array('constraint' => 81, 'type' => 'varchar'),
			'temp_password'       => array('constraint' => 81, 'type' => 'varchar'),
			'remember_me'         => array('constraint' => 81, 'type' => 'varchar'),
			'activation_hash'     => array('constraint' => 81, 'type' => 'varchar'),
			'last_login'          => array('constraint' => 11, 'type' => 'int', 'unsigned' => true ),
			'ip_address'          => array('constraint' => 50, 'type' => 'varchar'),
			'updated_at'          => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'created_at'          => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'status'              => array('constraint' => 1,  'type' => 'tinyint'),
			'activated'           => array('contsraint' => 1,  'type' => 'tinyint'),
		), array('id'), true, 'InnoDB');

		\DBUtil::create_table(\Config::get('sentry.table.users_metadata'), array(
			'id'         => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id'    => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'first_name' => array('constraint' => 50, 'type' => 'varchar'),
			'last_name'  => array('constraint' => 50, 'type' => 'varchar'),
		), array('id'), true, 'InnoDB', null , array(
			array(
				'key' => 'user_id',
				'reference' => array(
					'table' => \Config::get('sentry.table.users'),
					'column' => 'id'
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
			)
		));

		\DBUtil::create_table(\Config::get('sentry.table.groups'), array(
			'id'       => array('constraint' => 11,  'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name'     => array('constraint' => 200, 'type' => 'varchar'),
			'level'    => array('constraint' => 11,  'type' => 'int'),
			'is_admin' => array('constraint' => 1,   'type' => 'tinyint'),
		), array('id'), true, 'InnoDB');

		\DBUtil::create_table(\Config::get('sentry.table.users_suspended'), array(
			'id'              => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'login_id'        => array('constraint' => 50, 'type' => 'varchar'),
			'attempts'        => array('constraint' => 50, 'type' => 'int'),
			'ip'              => array('constraint' => 25, 'type' => 'varchar'),
			'last_attempt_at' => array('constraint' => 11, 'type' => 'int'),
			'suspended_at'    => array('constraint' => 11, 'type' => 'int'),
			'unsuspend_at'    => array('constraint' => 11, 'type' => 'int'),
		), array('id'), true, 'InnoDB');

		\DBUtil::create_table(\Config::get('sentry.table.users_groups'), array(
			'id'       => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'auto_increment' => true),
			'user_id'  => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'group_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
		), array('id'), true, 'InnoDB', null , array(
			array(
				'key' => 'user_id',
				'reference' => array(
					'table' => \Config::get('sentry.table.users'),
					'column' => 'id'
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
			),
			array(
				'key' => 'group_id',
				'reference' => array(
					'table' => \Config::get('sentry.table.groups'),
					'column' => 'id'
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
			)
		));

	}

	public function down()
	{
		\Config::load('sentry', true);

		\DBUtil::drop_table(\Config::get('sentry.table.users_metadata'));
		\DBUtil::drop_table(\Config::get('sentry.table.users_groups'));
		\DBUtil::drop_table(\Config::get('sentry.table.users_suspended'));
		\DBUtil::drop_table(\Config::get('sentry.table.users'));
		\DBUtil::drop_table(\Config::get('sentry.table.groups'));

	}
}
