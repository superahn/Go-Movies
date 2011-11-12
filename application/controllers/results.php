<?php
// include the tmdb class
    //include('libraries/tmdb.php');
	$this->load->library("TMDb");

    //if you prefer using 'xml'
    $tmdb_xml = new TMDb('4a4a651d48585920dbdb71f83faedb3c', TMDb::XML);

    //Title to search for
    $title = $_POST['title'];

    //Search Movie with default return format
    $xml_movies_result = $tmdb_xml->searchMovie($title);

    $xml = simplexml_load_string($xml_movies_result);

    echo '<table>';
    echo '<tr>';
    echo '<th>Cover</th>';
    echo '<th>Info</th>';
    echo '</tr>';
    foreach ($xml->movies->movie as $movie) {
		$moviename = $movie->name;
		$imdbid = $movie->imdb_id;
		$posterimg = $movie->images->image[3]['url'];  

		echo '<tr>';
		if (!empty($posterimg)) {
			echo '<td><a target="_blank" href="'.$movie->url.'"><img src="'.$posterimg.'" alt="'.$moviename.'" /></a></td>';
		} else {
			echo '<td>No image</td>';
		}
		echo '<td><a target="_blank" href="'.$movie->url.'"><strong>'.$moviename.'</strong></a> ('.substr($movie->released, 0, 4).')<br /><br />';
		echo $movie->overview;
		echo '</td>';
		echo '</tr>';
    }
    echo '</table>';
?>