<?php

/*
 * Fork from https://github.com/BauerUK/Rotten-Potatoes
 * v0.2-1
 * Added get_box_office function *
 */
 
/**
 * Ideas/considerations
 * 
 * v0.1	
 * ----
 * - caching	
 *  - magic getting for non return features, e.g. movies don't have reviews
 *  - retrieving images (as blob)
 * 
 * v0.2
 * ----
 * - standardized responses (classes)
 * - documentation
 * 
 */

/**
 * Main RottenTomatoes class
 * 
 * <b>Example:<b/>
 * 
 * <pre>
 * // make a new rp instance
 * $rp = new rotten_potatoes($config);
 * 
 * // perform a search
 * $search = $rp->search("the matrix");
 * 
 * // retrieve the result
 * $movie = $rp->movies[$search->results[0]];
 * </pre>
 */
class rotten_potatoes {

	/**
	 * The current major Rotten Potatoes version
	 */
	const VERSION = "0.2";
	
	/**
	 * The presently implemented API version
	 */
	const API_VERSION = "v1.0";
	
	// @todo This is an inelegant solution, but it's working at the moment
	
	/**
	 * The main API list
	 */
	const API_URL_TMPL_MAIN = "http://api.rottentomatoes.com/api/public/{api-version}.{format}?apikey={api-key}";
	
	/**
	 * API page of some sort
	 */
	const API_URL_TMPL_PAGE = "http://api.rottentomatoes.com/api/public/{api-version}/{page}.{format}?apikey={api-key}";
	
	/**
	 * Search
	 */
	const API_URL_TMPL_SEARCH = "http://api.rottentomatoes.com/api/public/{api-version}/movies.{format}?apikey={api-key}&{query-args}";
	
	/**
	 * Movie details
	 */
	const API_URL_TMPL_MOVIE = "http://api.rottentomatoes.com/api/public/{api-version}/movies/{movie-id}.json?apikey={api-key}";
	
	/**
	 * Box Office Movies
	 */
	const API_URL_TMPL_BOXOFFICE = "http://api.rottentomatoes.com/api/public/{api-version}/lists/movies/box_office.json?apikey={api-key}&{query-args}";
	
	/**
	 * Collection of rt_movie, implements ArrayAccess and dynamically fetches 
	 * movies upon access.
	 * 
	 * @var array
	 */
	public $movies;
	
	/**
	 * Default config
	 * 
	 * @var array
	 */
	private $config_default = array (
	
		"API_KEY" => ""
		
	);
	
	/**
	 * Default built list of template arguments for making requests
	 * @var array
	 */
	private $tmpl_args;
	
	/**
	 * Instance configuration
	 * @var array
	 */
	private $config;

	/**
	 * Key/value list of configuration options for this instance.
	 * Accepted values:
	 * - <b>API_KEY</b> - Your RottenTomatoes API key. Required.
	 * 
	 * @param array $config 
	 */
	function __construct($config=array()) {
		
		$this->config = array_merge($this->config_default, $config);
		
		if(empty($this->config["API_KEY"])) {
			throw new Exception("RottenTomateos API key needed");
		}
		
		$this->tmpl_args = array (
			"format"		=> "json",
			"api-version"	=> self::API_VERSION,
			"api-key"		=> $this->config["API_KEY"]
		);
		
		$this->movies = new rt_movie_collection($this);
		
	}
	
	/**
	 * Make a movie search based on a query
	 * 
	 * @param array $args The search query arguments. <b>Required</b>
	 * @param int $year Search within a specific year. <b>Optional</b>
	 * @param int $page_limit Results per page. <b>Optional</b>
	 * @param int $page Which page to search within. <b>Optional</b>
	 * 
	 * @return rt_search_result The search result information.
	 */
	function search($query, $year=NULL, $page_limit=5, $page=NULL) {
		
		$args = array (
			"q" => $query,
			"year" => (int)$year,
			"page_limit" => $page_limit,
			"page" => $page
		);
		
		$json = $this->request(self::API_URL_TMPL_SEARCH, array(
			"query-args" => $this->collapse_args($args)
		));
		
		if(isset($json->total)) {
			
			if($json->total > 0) {
				
				return new rt_search_result($json);
				
			} else {
				
				return array();
				
			}
			
		} else {
			
			return NULL;
			
		}
		
	}	
	
	/**
	 * Gets movie details by a movie ID.
	 * 
	 * @param string $movie_id The movie ID
	 * @return rt_movie The resulting movie object
	 */
	public function get_movie($movie_id) {
		
		$tmpl_args = array (
			"movie-id" => $movie_id
		);
		
		$json = $this->request(self::API_URL_TMPL_MOVIE, $tmpl_args);
				
		if(isset($json->id) and !empty($json->id)) {
			return new rt_movie($json, $this);
		} else {
			return NULL;
		}
		
	}
	
	/**
	 * Gets box office movies in your country.
	 * 
	 * @param int $limit Limits the number of box office movies returned. <b>Optional</b>
	 * @param string $country Country (ISO 3166-1 alpha-2) if available. Otherwise, returns US data. <b>Optional</b>
	 */
	public function get_box_office($limit=10, $country=NULL) {
		
		$args = array (
			"limit" => $limit,
			"country" => $country
		);
		
		$json = $this->request(self::API_URL_TMPL_BOXOFFICE, 
			array("query-args" => $this->collapse_args($args))
		);
		return $json;
	}
	
	/**
	 * Performs a request, populating required fields in a template format,
	 * @param string $fmt The template format
	 * @param array $args The template arguments
	 * 
	 * @return mixed If succesful, the JSON object response. If not, NULL.
	 */
	private function request($fmt, $args=array()) {
		
		// build up an array of template arguments
		$tmpl_args = array_merge($this->tmpl_args, $args);
		
		// build the templated URL -- this is the URL we'll now request
		$request_url = $this->tmpl($fmt, $tmpl_args);

		$response = NULL;
		
		try 
		{		
			$response = file_get_contents($request_url);
			$response = json_decode($response);
		} 
		catch(Exception $ex) 
		{
			$response = NULL;
			// todo: some kind of error handling
		}
		
		return $response;
				
	}
	
	/**
	 * Fills in a template theme
	 * 
	 * @param string $tmpl The template
	 * @param array $args The template args
	 * @return string The formatted string
	 */
	function tmpl($tmpl, $args=array()) {
		
		foreach($args as $name => $value) {
			$tmpl = preg_replace("/{" . preg_quote($name) . "}/", $value, $tmpl);
		}
		
		return $tmpl;
		
	}
	
	/**
	 * Collapses a key/value collection into a URL-encoded query string
	 * 
	 * @param array $args The key-value collaction to collapse
	 * @return string Query string
	 */
	function collapse_args($args) {
		
		$kv = array();
		
		foreach($args as $key => $value) {
			$kv[] = urlencode($key) . "=" . urlencode($value);
		}
		
		return implode("&", $kv);
		
	}
	
}

/**
 * Contains search result information.
 */
class rt_search_result {
	
	/**
	 * A collection of movie IDs that were returned by the search
	 * @var array
	 */
	public $results;
	
	/**
	 * The total amount of results
	 * @var int
	 */
	public $total_results;
	
	/**
	 * The API URL for the next set of results
	 * @var type 
	 */
	public $api_next_page;
	
	/**
	 * The current page number
	 * @var int
	 */
	public $api_current_page;
	
	/**
	 *
	 * @param object $json The returned RottenTomatoes JSON search results
	 */
	function __construct($json) {
			   
		$this->results = array();
		
		$this->total_results = $json->total;
		
		foreach($json->movies as $movie) {

			preg_match("/\/(\d+)\.json$/", $movie->links->self, $matches);
			
			$this->results[] = $matches[1];
			
		}
		
		if(!empty($json->links->next)) {
			
			$this->api_next_page = $json->links->next;
			
		} else {
			
			$this->api_next_page = NULL;
			
		}
		
		$this->api_current_page = $json->links->self;
		
	}
	
}

/**
 * 
 */
class rt_movie_collection implements ArrayAccess {
	
	/**
	 * Internal reference to the parent RP instance
	 * @var rotten_potatoes
	 */
	private $rp;
	
	/**
	 * Collection of movies that have been searched for this session
	 * @todo Hand this over to a DB or file of some sort for long-term caching?
	 * @var array
	 */
	private $movie_cache;
	
	/**
	 *
	 * @param rotten_potatoes $rp Current RP instance
	 */
	function __construct(&$rp) {
		
		$this->rp =& $rp;
		
	}
	
	public function offsetExists($offset) {
		return $this->movie_cache[$offset];
	}
	
	public function offsetGet($offset) {
		$this->movie_cache[$offset] = $this->rp->get_movie($offset);
		return $this->movie_cache[$offset];
	}
	
	public function offsetSet($offset, $value) {
		$this->movie_cache[$offset] = $value;
	}
	
	public function offsetUnset($offset) {
		unset($this->movie_cache[$offset]);
	}
	
}

/**
 * Movie information
 */
class rt_movie {
	
	/**
	 * The Rotten Tomatoes movie ID
	 * @var string
	 */
	public $id;
	
	/**
	 * The title of the movie
	 * @var string
	 */
	public $title;
	
	/**
	 * The year of release
	 * @var string
	 */
	public $year;
	
	/**
	 * A string collection of genres
	 * @var array
	 */
	public $genres;
	
	/**
	 * The MPAA rating 
	 * @var string
	 */
	public $mpaa_rating;
	
	/**
	 * Runtime (in minutes)
	 * @var int
	 */
	public $runtime;
	
	/**
	 * Collection of release dates. Possible keys:
	 * 
	 *  - <b>theater</b> - Theater release date
	 *  - <b>dvd</b> - DVD release date
	 * 
	 * @todo Standardize in a class
	 * @var array
	 */
	public $release_dates;
	
	/**
	 * Collection of ratings. Possible keys:
	 * 
	 * - <b>critics_score</b> - The critics rating
	 * - <b>audience_score</b> - Users' ratings
	 * 
	 * @todo Standardize to a class
	 * @var array
	 */
	public $ratings;
	
	/**
	 * Plot synopsis (if license permits distribution, or empty string if not)
	 * @var string
	 */
	public $synopsis;
	
	/**
	 * Collection of URLs to posters
	 * @var array
	 */
	public $posters;
	
	/**
	 * Collection of cast details (abridged)
	 * @todo Standardize and allow for full cast retrieval
	 * @var array
	 */
	public $abridged_cast;
	
	/**
	 * Collection of directors (abridged)
	 * @todo Standardize and allow for full director list retrieval
	 * @var array
	 */
	public $abridged_directors;
	
	/**
	 * URL to the RottenTomatoes profile page
	 * @var string 
	 */
	public $rt_page;
		
	// magically accessed only, left here for 
	public $cast;
	public $reviews;
	
	// store a formatted array of links that we can access
	private $links;
	
	/**
	 * Internal reference to the current RP instance
	 * @var rotten_potatoes
	 */
	private $rp;
	
	function __construct($json, &$rp) {
		
		$this->rp =& $rp;
		
		// remove local variables that will be handled with `__get`
		unset($this->cast);
		unset($this->reviews);
		
		$this->id					= (string)	$json->id;
		$this->title				= (string)	$json->title;
		$this->year					= (string)	$json->year;
		$this->genres				= (array)	$json->genres;
		$this->mpaa_rating			= (string)	$json->mpaa_rating;
		$this->runtime				= (int)		$json->runtime;
		$this->release_dates		= (array)   $json->release_dates;
		$this->ratings				= (array)   $json->ratings;
		$this->synopsis				= (string)	$json->synopsis;
		$this->posters				= (array)	$json->posters;
		$this->abridged_cast		= (array)	$json->abridged_cast;
		$this->abridged_directors	= (array)	$json->abridged_directors;
		$this->links				= (array)	$json->links;
		$this->rt_page				= (string)	$json->links->alternate;
		
	}
	
	function __get($name) {
		
		switch($name) {
		
			case "cast":
				
				// get the cast list
				
				break;
				
			case "reviews":
				
				// get the reviews
				
				break;
				
		}
		
	}
		
}