<?php
App::uses('Hash', 'Model');

/**
 * Hash Test Case
 *
 */
class HashTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.hash');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Hash = ClassRegistry::init('Hash');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Hash);

		parent::tearDown();
	}

}
