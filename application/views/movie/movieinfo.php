<div data-role="page" data-add-back-btn="true" class="type-home">  

	<div data-role="header">
		<h1><?php echo $movie->title; ?></h1>
	</div>  

	<div data-role="content">  
    	<!-- Box Office Movies -->
  		<section>
            <ul data-role="listview" id="poster_profile">
				<!-- BEGINNING OF <li> for INTRO -->
				<li data-icon="false"><a href="#">	
                			
				<!-- Movie poster (profile size) -->
				<img src="<?php echo $movie->posters['profile']; ?>" />
				
				<!-- Movie title -->
				<h3 class="text-wrap"><?php echo $movie->title.' ('.$movie->year.')' ?></h3>
                
                <!-- Abridged cast -->
                <?php
                $cast_string = "";
                $last_key = end(array_keys($movie->abridged_cast));
                foreach($movie->abridged_cast as $key => $cast)
                {
                    $cast_string .= $cast->name;
                    if($key != $last_key) {
                        $cast_string .= ", ";
                    }
                }						
                echo '<p class="text-wrap">'.$cast_string.'</p>';
				?>
				
				<!-- rating -->
				<p><?php echo $movie->ratings['critics_rating']. ' ' .$movie->ratings['critics_score'].'%' ?></p>	
                			
				</a></li>
                
				<!-- BEGINNING OF <li> for PHOTOS -->
				<li data-role="list-divider">Photos</li>
				
				<!-- BEGINNING OF <li> for DETAILS -->
				<li data-role="list-divider">Details</li>
				<li>
                	<h3>Synopsis</h3>
                    <p class="text-wrap"><?php echo $movie->synopsis ?></p>
                </li>
                <li>
                	<p><strong>Rated: </strong><?php echo $movie->mpaa_rating ?></p>
                	<p><strong>Running Time: </strong><?php echo $movie->runtime ?> mins</p>
                    <?php
					if(!empty($movie->release_dates['theater']))
                		echo '<p><strong>Release Date: </strong>'.$movie->release_dates['theater']. '</p>';
					if(!empty($movie->release_dates['dvd']))
                		echo '<p><strong>Release Date: </strong>'.$movie->release_dates['dvd']. ' (DVD)</p>';
					?>
                </li>
				
				<!-- BEGINNING OF <li> for TOP CASTING -->
				<li data-role="list-divider">Cast</li>
            </ul>
		</section>      
	</div>
	
	<div data-role="footer">
			<h4>&copy; WebAppDev.net</h4>
	</div>	
</div>
