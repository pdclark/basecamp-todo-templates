<?php

class HomeController extends BaseController {

	public function index() {
		$template_name = 'orientation.md';

		return View::make(
			'home',
			array(
				'list_count' => 0,
				'templates' => new TodoTemplatesController(),
			)
		);

	}

}
