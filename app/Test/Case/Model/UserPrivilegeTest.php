<?php
App::uses('UserPrivilege', 'Model');

/**
 * UserPrivilege Test Case
 *
 */
class UserPrivilegeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.user_privilege', 'app.user', 'app.role', 'app.company', 'app.country', 'app.state');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserPrivilege = ClassRegistry::init('UserPrivilege');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserPrivilege);

		parent::tearDown();
	}

}
