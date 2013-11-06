<?php
App::uses('Timeline', 'Model');

/**
 * Timeline Test Case
 *
 */
class TimelineTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.timeline', 'app.monitor', 'app.video');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Timeline = ClassRegistry::init('Timeline');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Timeline);

		parent::tearDown();
	}

}
