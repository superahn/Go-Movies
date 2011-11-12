<?php
class Movies extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('movies_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	public function home()
	{
		$data['title'] = "Go Movies";
		$this->load->view('jqmtemp/header', $data);	
		$this->load->view("movies/home", $data);
		$this->load->view('jqmtemp/footer');	
	}
	
	public function search()
	{
		$data['title'] = 'Search Movies';
		
		$this->load->view('jqmtemp/header', $data);	
		$this->load->view("movies/search", $data);
		$this->load->view('jqmtemp/footer');	
	}		
	
	public function search_response()
	{
		$title = $this->input->get('title');
		if (empty($title))
		{
			redirect('movies/search');
		}
		
		$data['title'] = ucwords($title);
		$data['xml'] = $this->movies_model->get_movies($title);
		
		$this->load->view('jqmtemp/header', $data);	
		$this->load->view('movies/query', $data);
		$this->load->view('jqmtemp/footer');			
	}
	
	public function info($mid)
	{
		$data['title'] = 'Movie info';
		$data['xmlMovie'] = $this->movies_model->get_movie_info($mid);
		$data['xmlImages'] = $this->movies_model->get_movie_images($mid);
		
		$this->load->view('jqmtemp/header', $data);	
		$this->load->view('movies/movie_info', $data);
		$this->load->view('jqmtemp/footer');			
	}
}

?>