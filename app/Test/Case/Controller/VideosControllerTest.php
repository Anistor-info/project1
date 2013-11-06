<?php
App::uses('VideosController', 'Controller');

/**
 * TestVideosController *
 */
class TestVideosController extends VideosController {
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
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * VideosController Test Case
 *
 */
class VideosControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.video', 'app.uploaded_user', 'app.monitor', 'app.monitors_video', 'app.processing', 'app.processing_video');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Videos = new TestVideosController();
		$this->Videos->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Videos);

		parent::tearDown();
	}

}
