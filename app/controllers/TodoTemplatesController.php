<?php

class TodoTemplatesController extends BaseController {

	public $template_dir = '/todo-templates/';
	protected $name_pattern = '/^\(([a-zA-Z0-9 ]*)\)/';

	protected $templates = array();


	function __construct() {
		$this->template_dir = dirname( __DIR__ ) . $this->template_dir;
	}

	public function getTemplates() {
		if ( ! empty( $this->templates ) ) {
			return $this->templates;
		}

		foreach( glob( $this->template_dir . '*.md' ) as $path ) {
			$this->templates = array_merge( $this->templates, $this->getTemplateAsArray( $path ) );
		}

		return $this->templates;
	}

	public function getTemplateAsArray( $path ) {
		if ( false === strpos( $path, $this->template_dir ) ) {
			$path = $this->template_dir . $path;
		}

		$template = array();
		$basename = basename( $path );

		$template[ $basename ]['file'] = $basename;

		foreach( preg_split( "/((\r?\n)|(\r\n?))/", file_get_contents( $path ) ) as $line ){

			// Skip blank lines
			if ( ! isset( $line[0] ) ) { continue; }

			// Switch based on first character of line
			switch( $line[0] ) {

				// Heading: Todo list title
				case '#':
					$template[ $basename ]['title'] = $this->getListTitle( $line );
					break;

				// Todo list item
				case '*':
					$template[ $basename ]['items'][] = $this->getListItem( $line );
					break;

				default:
					// Skip all other lines
					continue;
					break;
			}
		}

		return $template;
	}

	protected function getListTitle( $title ) {
		$title = $this->maybeFindReplace( $title );

		return trim( ltrim( $title, '#' ) );
	}

	protected function getListItem( $content ) {
		$content = $this->maybeFindReplace( $content );
		$content = trim( ltrim( $content, '#* ' ) );

		return array(
			'content'  => $this->removeNameFromContent( $content ),
			'assignee' => $this->getNameFromContent( $content ),
		);
	}

	protected function parseFindReplaceInput() {
		static $find_replace;

		if ( ! isset( $find_replace ) ) {
			parse_str( Input::get( 'find_replace' ), $find_replace );
			$find_replace = array_shift( $find_replace );
		}

		return $find_replace;
	}

	public function maybeFindReplace( $content ) {
		if ( Input::has( 'find_replace' ) ) {

			$find_replace = $this->parseFindReplaceInput();

			$content = str_replace(
				array_keys( $find_replace ),
				array_values( $find_replace ),
				$content
			);
		}

		return $content;
	}

	protected function removeNameFromContent( $content ) {
		return trim( preg_replace( $this->name_pattern, '', $content ) );
	}

	public function getNameFromContent( $content ) {
		preg_match( $this->name_pattern, $content, $match );

		return isset( $match[1] ) ? trim( $match[1] ) : false;
	}

}