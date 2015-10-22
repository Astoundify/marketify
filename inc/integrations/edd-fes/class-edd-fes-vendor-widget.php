<?php

class Marketify_EDD_FES_Vendor_Widget extends Marketify_Widget {

    public $fes;
    public $vendor;

    public function __construct() {
        $this->fes = marketify()->get( 'edd-fes' );
        $this->vendor = $this->fes->vendor();

        parent::__construct();
    }

}

