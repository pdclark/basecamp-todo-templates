<?php

class BasecampProjectsTodolistController extends BasecampController {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function store( $projectId ) {

		return Response::json(
			$this->api->createTodolistByProject(
				array(
					'projectId' => $projectId,
					'name' => Input::get( 'name' ),
					'description' => Input::get( 'description' ),
				)
			)
		);

	}

}
