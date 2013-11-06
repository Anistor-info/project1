<?php
App::uses('PrivilegeSet', 'Model');

/**
 * PrivilegeSet Test Case
 *
 */
class PrivilegeSetTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.privilege_set', 'app.set');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PrivilegeSet = ClassRegistry::init('PrivilegeSet');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PrivilegeSet);

		parent::tearDown();
	}

}
