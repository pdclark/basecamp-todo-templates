<?php

class BasecampProjectsTodolistTodosController extends BasecampController {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function store( $project_id, $todo_list_id ) {
		Response::json( $this->storeDirect( $project_id, $todo_list_id ) );
	}

	public function storeWithoutResponse( $project_id, $todo_list_id ) {
		if ( Input::has( 'file' ) ) {

			$args = array(
				'project_id' => $project_id,
				'todo_list_id' => $todo_list_id,
				'file' => Input::get( 'file' ),
			);

			$response = $this->storeTodosFromTemplate( $args );
			$response['file'] = Input::get( 'file' );

		}else {

			$accesses = BasecampProjectsAccessesController::get_instance();
			$accesses->setProjectId( $project_id );

			$args = array(
				'project_id' => $project_id,
				'todo_list_id' => $todo_list_id,
				'content' => Input::get( 'content' ),
				'assignee' => $accesses->getIdByName( Input::get( 'assignee' ) ),
			);

			$response = $this->storeTodo( $args );

		}

		return $response;
	}

	/**
	 * @param  array  $args array( 'project_id', 'todo_list_id', 'content', 'assignee' )
	 */
	public function storeTodosFromTemplate( $args = array() ) {
		$template = new TodoTemplatesController();
		$response = array();

		$todo_list = $template->getTemplateAsArray( $args['file'] );
		$todo_list = array_shift( $todo_list );

		$accesses = BasecampProjectsAccessesController::get_instance();
		$accesses->setProjectId( $args['project_id'] );

		foreach( $todo_list['items'] as $todo ) {

			$todo_args = array(
				'project_id'   => $args['project_id'],
				'todo_list_id' => $args['todo_list_id'],
				'content'      => $todo['content'],
				'assignee'     => $accesses->getIdByName( $todo['assignee'] ),
			);

			$response[] = $this->storeTodo( $todo_args );
		}

		return $response;

	}

	/**
	 * @param  array  $args array( 'project_id', 'todo_list_id', 'content', 'assignee' )
	 */
	public function storeTodo( $args = array() ) {
		$todo_data = array(
			'projectId' => $args['project_id'],
			'todolistId' => $args['todo_list_id'],
			'content' => $args['content'],
		);

		if ( ! empty( $args['assignee'] ) ) {
			$todo_data['assignee'] = array( 'id' => $args['assignee'], 'type' => 'Person' );
		}

		var_dump( $todo_data );

		return $this->api->createTodoByTodolist( $todo_data );
	}

}
