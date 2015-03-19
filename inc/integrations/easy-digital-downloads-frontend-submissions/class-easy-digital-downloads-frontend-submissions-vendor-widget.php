<?php

class Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendor_Widget extends Marketify_Widget {

	public $fes;
	public $vendor;

	public function __construct() {
		$this->fes = Marketify::get( 'easy-digital-downloads-frontend-submissions' );
		$this->vendor = $this->fes->vendor();

		parent::__construct();
	}

}

