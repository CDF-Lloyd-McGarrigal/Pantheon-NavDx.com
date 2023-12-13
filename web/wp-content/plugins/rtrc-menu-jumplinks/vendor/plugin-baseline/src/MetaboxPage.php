<?php

namespace BaselinePlugin;

class MetaboxPage {

	use traits\ExistingAdminPagesTrait;

	protected $pageDefinition = [];
	protected $fields = [];

	function __construct( $args ){

		$this->pageDefinition = $args;

		add_filter( 'mb_settings_pages', [$this, 'registerSettingsPage'] );
		add_filter( 'rwmb_meta_boxes', [$this,'registerMetaboxes'] );
	}

	public function registerSettingsPage( $settingsPages ) {

		$settingsPages[] = $this->pageDefinition;

		return $settingsPages;
	}

	public function registerMetaboxes( $metaboxes ){

		$metaboxes[] = array(
			'id'				=> $this->getPageSlug() . '_settings',
			'title'				=> $this->getPageTitle() . ' Settings',
			'settings_pages'	=> $this->getPageSlug(),
			'fields'			=> $this->fields
		);

		return $metaboxes;
	}

	public function registerField( $field ){

		$this->fields[] = $field;
	}

	public function registerManyFields( $fields ){

		$this->fields = array_merge( $this->fields, $fields );
	}

	public function getPageSlug(){

		return $this->pageDefinition['id'];
	}

	public function getPageTitle(){

		return isset($this->pageDefinition['menu_title']) 
			? $this->pageDefinition['menu_title'] 
			: $this->getPageSlug();
	}
}