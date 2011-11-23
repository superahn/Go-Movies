<div data-role="page" class="type-home">  

	<div data-role="header">
		<h1><?php echo $title; ?></h1>
	</div>  

	<div data-role="content">  
    	<!-- Box Office Movies -->
  		<section>
            <ul data-role="listview" id="poster_thumb">
				<li data-role="list-divider">Box Office (Australia)</li>
            	<?php
                foreach($result->movies as $key => $movie)
                {
                    echo '<li data-icon="false"><a href="' .base_url('movie/movieinfo/'.$movie->id) .'">';
					
					// Movie poster
                    echo '<img src="' .$movie->posters->thumbnail. '" />';
					
					// Movie title
                    echo '<h3>'.($key+1).'. '.$movie->title.'</h3>';
					
					// Abridged cast
					$cast_string = "";
					$last_key = end(array_keys($movie->abridged_cast));
					foreach($movie->abridged_cast as $key => $cast)
					{
						$cast_string .= $cast->name;
						if($key != $last_key) {
							$cast_string .= ", ";
						}
					}						
					echo '<p>'.$cast_string.'</p>';
					
					// rating
                    $critics_rating = $movie->ratings->critics_rating;
                    $critics_score = $movie->ratings->critics_score;
					echo '<p>'.$critics_rating. ' ' .$critics_score.'%</p>';
					
                    echo '</a></li>';
                }
				?>
            </ul>
		</section>      
	</div>
	
	<div data-role="footer">
			<h4>&copy; WebAppDev.net</h4>
	</div>	
</div>
