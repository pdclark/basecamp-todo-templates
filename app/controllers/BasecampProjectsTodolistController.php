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
	public function store( $project_id ) {

		$template = new TodoTemplatesController();

		$todo_list_response = $this->api->createTodolistByProject( array(
			'projectId' => $project_id,
			'name' => $template->maybeFindReplace( Input::get( 'name' ) ),
			'description' => $template->maybeFindReplace( Input::get( 'description' ) ),
		) );


		// If a template file was provided, add all todo items to the todo list
		if ( Input::has( 'file' ) ) {
			$todo_list_response['file'] = Input::get( 'file' );
			$todos = new BasecampProjectsTodolistTodosController();

			$todo_list_response['todos'] = $todos->store( $project_id, $todo_list_response['id'] );
		}

		return Response::json( $todo_list_response );

	}

}
