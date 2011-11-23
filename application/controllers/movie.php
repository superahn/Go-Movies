<?php
class Movie extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('movie_model', 'MovieModel');
	}
	
	public function boxoffice()
	{
		$data['siteName'] = "Go Movies";
		$data['title'] = "Box Office";
		$data['result'] = $this->MovieModel->GetBoxOfficeMovies(10, 'au');
		$this->load->view('templates/header', $data);	
		$this->load->view("movie/boxoffice", $data);
		$this->load->view('templates/footer');	
	}
	
	public function movieinfo($id)
	{
		$data['siteName'] = "Go Movies";
		$data['title'] = 'Movie info';
		$data['movie'] = $this->MovieModel->GetMovieInfo($id);
		//$data['xmlImages'] = $this->MovieModel->get_movie_images($mid);
		
		$this->load->view('templates/header', $data);	
		$this->load->view('movie/movieinfo', $data);
		$this->load->view('templates/footer');			
	}
	
	public function search()
	{
		$data['siteName'] = "Go Movies";
		$data['title'] = 'Search Movies';
		
		$this->load->view('templates/header', $data);	
		$this->load->view("movie/search", $data);
		$this->load->view('templates/footer');	
	}		
	
	public function search_response()
	{
		$title = $this->input->get('title');
		if (empty($title))
		{
			redirect('movie/search');
		}
		
		$data['siteName'] = "Go Movies";
		$data['title'] = ucwords($title);
		$data['xml'] = $this->Movies_model->Search($title);
		
		$this->load->view('templates/header', $data);	
		$this->load->view('movie/query', $data);
		$this->load->view('templates/footer');			
	}
}

?>