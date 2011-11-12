<?php

class JqmTuts extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->view("jqmtemp/header");
		$this->load->view("jqmtuts/index");
		$this->load->view("jqmtemp/footer");
	}
}

?>