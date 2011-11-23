<?php
	$movie = $xmlMovie->movies->movie[0];
	$moviename = $movie->name;
	$posterimg = $movie->images->image[3]['url'];
?>

<div data-role="page" data-add-back-btn="true">  

	<div data-role="header">
		<h1><?php echo $title; ?></h1>
	</div>  

	<div data-role="content">  
		<section class="overview">
			<div class="poster">
				<img src="<?php echo $posterimg ?>" alt="<?php echo $moviename ?>" />
			</div>
			<div class="mainInfo">
				<h3><?php echo $moviename.' ('.substr($movie->released, 0, 4).')' ?></h3>
				<p><?php echo $movie->tagline ?></p>
			</div>
			<div class="clear"></div>
		</div>
		<section class="photos">
			<?php
			echo '<h3>Posters</h3>';
			foreach ($xmlImages->movies->movie->images->poster as $poster) {
				foreach ($poster->image as $image) {
					if($image['size'] == "thumb") {
						echo '<img src="'.$image['url'].'" />';
					}
				}
			} 
			echo '<h3>Backdrop</h3>';
			foreach ($xmlImages->movies->movie->images->backdrop as $backdrop) {
				foreach ($backdrop->image as $image) {
					if($image['size'] == "thumb") {
						echo '<img src="'.$image['url'].'" />';
					}
				}
			} 
			?>
		</div>
		<!--<section class="details"></div>-->
		<section class="categories"></div>
		<section class="topCasting"></div>
		<section class="topStaff"></div>
	</div>
	
	<div data-role="footer" data-position="fixed">
			<h4>&copy; WebAppDev.net</h4>
	</div>
	
</div>
