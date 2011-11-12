<?php

class Movies_model extends CI_Model 
{
	public function __construct() 
	{
		$this->load->library("TMDb");
	}
	
	public function get_movies($title) 
	{
		//if you prefer using 'xml'
		$tmdb_xml = new TMDb('4a4a651d48585920dbdb71f83faedb3c', TMDb::XML);
		
		//Search Movie with default return format
		$xml_movies_result = $tmdb_xml->searchMovie($title);

		return simplexml_load_string($xml_movies_result);
	}
	
	public function get_movie_info($mid)
	{
		//if you prefer using 'xml'
		$tmdb_xml = new TMDb('4a4a651d48585920dbdb71f83faedb3c', TMDb::XML);
		
		//Movie.getInfo with default return format
		$xml_result = $tmdb_xml->getMovie($mid);

		return simplexml_load_string($xml_result);
	}
	
	public function get_movie_images($mid)
	{
		//if you prefer using 'xml'
		$tmdb_xml = new TMDb('4a4a651d48585920dbdb71f83faedb3c', TMDb::XML);
		
		//Movie.getImages with default return format
		$xml_result = $tmdb_xml->getImages($mid);

		return simplexml_load_string($xml_result);
	}
}

?>