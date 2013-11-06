<?php
App::uses('CompaniesController', 'Controller');

/**
 * TestCompaniesController *
 */
class TestCompaniesController extends CompaniesController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 *
 @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * CompaniesController Test Case
 *
 */
class CompaniesControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.company', 'app.country', 'app.state');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Companies = new TestCompaniesController();
		$this->Companies->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Companies);

		parent::tearDown();
	}

}
