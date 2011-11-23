<h1>Movie Search</h1> 
<form action="test_wrapper.php" method="post">
	<label for="query">Search</label>
	<input type="text" id="query" name="query" />
	<input type="submit" name="submit" value="Search" />
	<input type="submit" name="boxoffice" value="Box Office" />
</form>

<?php

	include("rotten_potatoes.php");

	$rp = new rotten_potatoes(array("API_KEY" => "6bwd3wxq83gxcg9ttd7mu4yr"));
	
	if(isset($_POST['boxoffice']))
	{
		// box office movies
		$result = $rp->get_box_office(5, 'au');
		
		echo '<ul>';
		foreach($result->movies as $movie)
		{
			$critics_rating = $movie->ratings->critics_rating;
			$critics_score = $movie->ratings->critics_score;
			echo '<li>';
			echo '<img src="' .$movie->posters->thumbnail. '" />';
			echo $movie->id.'. '.$movie->title.' ('.$movie->year.') '.$critics_rating. ' ' .$critics_score;
			echo '</li>';
		}
		echo '</ul>';
	}

	if(isset($_POST['query']) && !empty($_POST['query']))
	{
		// contains info about search results
		$search = $rp->search($_POST['query']);
	
		// get the first result
		echo '<ul>';
		foreach($search->results as $row)
		{
			$movie = $rp->movies[$row];
	
			echo '<li>'.$movie->id.'</li>';
			echo '<li>'.$movie->title.'</li>';
			echo '<li>'.$movie->year.'</li>';
			echo '<li>'.$movie->genres.'</li>';
			echo '<li>'.$movie->mpaa_rating.'</li>';
			echo '<li>'.$movie->runtime.'</li>';
			foreach($movie->release_dates as $rdate) {
				echo '<li>' .$rdate. '</li>';
			}
			echo '<li>'.$movie->ratings.'</li>';
			echo '<li>'.$movie->synopsis.'</li>';
			echo '<li>'.$movie->posters.'</li>';
			echo '<li>'.$movie->abridged_cast.'</li>';
			echo '<li><a href="'. $movie->rt_page. '">'. $movie->rt_page. '</a></li>';
			echo '<li>'.$movie->cast.'</li>';
			echo '<li>'.$movie->reviews.'</li>';
		}
		echo '</ul>';
	}
?>