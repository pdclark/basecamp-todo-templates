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

		if ( Input::has( 'file' ) ) {

			$args = array(
				'project_id' => $project_id,
				'todo_list_id' => $todo_list_id,
				'file' => Input::get( 'file' ),
			);

			$response = $this->storeTodosFromTemplate( $args );
			$response['file'] = Input::get( 'file' );

		}else {

			$args = array(
				'project_id' => $project_id,
				'todo_list_id' => $todo_list_id,
				'content' => Input::get( 'content' ),
				'assignee' => Input::get( 'assignee' ),
			);

			$response = $this->storeTodo( $args );

		}

		return Response::json( $response );

	}

	/**
	 * @param  array  $args array( 'project_id', 'todo_list_id', 'content', 'assignee' )
	 */
	public function storeTodosFromTemplate( $args = array() ) {
		$template = new TodoTemplatesController();
		$response = array();

		$todo_list = $template->getTemplateAsArray( $args['file'] );
		$todo_list = array_shift( $todo_list );

		foreach( $todo_list['items'] as $todo ) {

			$todo_args = array(
				'project_id'   => $args['project_id'],
				'todo_list_id' => $args['todo_list_id'],
				'content'      => $todo['content'],
				// 'assignee' => $todo['assignee_id'],
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

		return $this->api->createTodoByTodolist( $todo_data );
	}

}
