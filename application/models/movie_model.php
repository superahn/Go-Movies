<?php

class Movie_model extends CI_Model 
{
	public $api_key;
	
	/**
	 * Internal reference to the current RP instance
	 * @var rotten_potatoes
	 */
	private $rp;
	
	public function __construct() 
	{
        parent::__construct();
		
		$this->config->load('movie_settings');
		$this->api_key = $this->config->item('api_key');
		
		$this->load->library("rotten_potatoes", array("API_KEY" => $this->api_key));		
		$this->rp = new rotten_potatoes(array("API_KEY" => $this->api_key));
	}
	
	public function SearchMovies($query, $year=NULL, $page_limit=5, $page=NULL) 
	{
		//json result
		//Search Movie with query and other options
		$result = $this->rp->search($query, $year, $page_limit, $page);

		return $result;
	}
	
	public function GetMovieInfo($id) 
	{
		//json result
		//Search Movie with query and other options
		$result = $this->rp->get_movie($id);

		return $result;
	}
	
	public function GetBoxOfficeMovies($limit=10, $country=NULL) 
	{
		//json result
		//Search Movie with query and other options
		$result = $this->rp->get_box_office($limit, $country);

		return $result;
	}
}

?>