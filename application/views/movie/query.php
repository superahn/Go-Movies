<div data-role="page" data-add-back-btn="true">  

	<div data-role="header">
		<h1><?php echo $title; ?></h1>
	</div>  

	<div data-role="content">  
		<ul data-role="listview" data-filter="true">
			<?php
			foreach ($xml->movies->movie as $movie) {
				$moviename = $movie->name;
				$imdbid = $movie->id;//$movie->imdb_id;
				$posterimg = $movie->images->image[3]['url'];  

				if (empty($posterimg)) {
					$posterimg = base_url('content/images/no-poster-w92.jpg');
				}
			?>
				<li class="movie">
					<a href="info/<?php echo $imdbid ?>">
					<!--<a target="_blank" href="<?php echo $movie->url ?>">-->
					<img src="<?php echo $posterimg ?>" alt="<?php echo $moviename ?>" />
					<p><?php echo $movie->rating.' rating, '.$movie->votes.' votes' ?></p>
					<h3><?php echo $moviename.' ('.substr($movie->released, 0, 4).')' ?></h3>
					<p><?php echo $movie->overview ?></p>
					</a>
				</li>
			<?php 
			} ?>
		</ul>
	</div>
	
	<div data-role="footer" data-position="fixed">
			<h4>&copy; WebAppDev.net</h4>
	</div>
	
</div>
