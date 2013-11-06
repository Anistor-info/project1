<?php
App::uses('AssignMonitor', 'Model');

/**
 * AssignMonitor Test Case
 *
 */
class AssignMonitorTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.assign_monitor', 'app.monitor', 'app.user', 'app.role', 'app.company', 'app.country', 'app.state');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AssignMonitor = ClassRegistry::init('AssignMonitor');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AssignMonitor);

		parent::tearDown();
	}

}
