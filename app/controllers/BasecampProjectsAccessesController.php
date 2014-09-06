<?php

class BasecampProjectsAccessesController extends BasecampController {

	static private $_instance = null;

	protected $accesses = array();
	protected $project_id;

	public function __construct(){
		parent::__construct();
	}

	static public function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index( $project_id ) {
		$this->setProjectId( $project_id );
	}

	public function setProjectId( $project_id ) {
		$this->project_id = $project_id;
	}

	public function getIdByName( $new_name ) {
		$person_id = false;

		foreach ( $this->getAccesses() as $person ) {
			if ( $new_name == $person['name'] ) {
				$person_id = $person['id'];
			}
		}

		// No match. Try matching just by partial string
		if ( ! $person_id ) {
			foreach ( $this->getAccesses() as $person ) {
				if ( false !== strpos( $person['name'], $new_name ) ) {
					$person_id = $person['id'];
				}
			}
		}

		return $person_id;
	}

	protected function getNameById( $user_id ) {
		foreach ( $this->getAccesses() as $person ) {
			if ( $user_id == $person['id'] ) {
				return $person['name'];
			}
		}
		return false;
	}

	public function getAccesses() {
		if ( isset( $this->accesses[ $this->project_id ] ) ) {
			return $this->accesses[ $this->project_id ];
		}

		$this->accesses[ $this->project_id ] = $this->api->getAccessesByProject( array(
			'projectId' => $this->project_id,
		) );

		return $this->accesses[ $this->project_id ];
	}

}
