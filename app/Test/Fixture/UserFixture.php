<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'role_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'created_datetime' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'company_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index', 'comment' => 'Id of the company to which user belongs, null otherwise.'),
		'token' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 255, 'comment' => 'after registration set it to 0'),
		'indexes' => array('PRIMARY' => array('column' => 'user_id', 'unique' => 1), 'user_role' => array('column' => 'role_id', 'unique' => 0), 'company_user_belongs_to' => array('column' => 'company_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'user_id' => 1,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'email' => 'Lorem ipsum dolor sit amet',
			'first_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'role_id' => 1,
			'created_datetime' => '2012-04-18 06:50:25',
			'company_id' => 1,
			'token' => 1
		),
	);
}
