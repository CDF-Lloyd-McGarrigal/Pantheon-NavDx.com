<?php

namespace BaselinePlugin;

use \BaselinePlugin\AdminPage as AdminPage;

class AdminSubPage extends AdminPage {

	use traits\ExistingAdminPagesTrait;

	protected $requiredFields = array( 'title', 'slug', 'menuParent' );
	protected $menuParent = '';

	public function loadAdminPage(){

		add_submenu_page( 
			$this->menuParent, // The parent page
			$this->getTitle(), // The title
			$this->getMenuTitle(), // The menu title
			'manage_options', // The permissions @TODO: make this configurable
			$this->getSlug(), // The slug
			[$this, 'renderBase'] // The function to output the markup
		);
	}

	public function setParent( $new ){
		$this->menuParent = $new;
	}

	public function getParent(){
		return $this->menuParent;
	}
}