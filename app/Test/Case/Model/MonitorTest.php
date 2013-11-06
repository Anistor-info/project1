<?php
App::uses('Monitor', 'Model');

/**
 * Monitor Test Case
 *
 */
class MonitorTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.monitor', 'app.company', 'app.country', 'app.state', 'app.user', 'app.role', 'app.monitors_video', 'app.timeline', 'app.old', 'app.old_monitor', 'app.user_monitor');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Monitor = ClassRegistry::init('Monitor');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Monitor);

		parent::tearDown();
	}

}
