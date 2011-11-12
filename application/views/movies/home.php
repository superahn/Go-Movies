<script type="application/javascript" src="<?php echo base_url() ?>scripts/iScroll/iscroll.js"></script>
<script type="text/javascript">
	var movieScroll;
	var openningScroll;
	function loaded() {
		//myScroll = new iScroll('wrapper', { hScroll: false, hScrollbar: false, vScrollbar: false });
		
		movieScroll = new iScroll('carousel-movies', {
			snap: true,
			momentum: false,
			hScrollbar: false,
			onScrollEnd: function () {
				document.querySelector('#indicator > li.active').className = '';
				document.querySelector('#indicator > li:nth-child(' + (this.currPageX+1) + ')').className = 'active';
			}
		});

		openningScroll = new iScroll('wrapper-openning', { hScroll: false, hScrollbar: false, vScrollbar: false });
	}
	document.addEventListener('DOMContentLoaded', loaded, false);
</script>

<style type="text/css" media="all">

.subtitle > h2 {
	font-size: 0.9em;
	padding: 2px 2px 2px 10px;
	margin: 0px;
	background: #729AC1;
	display: block;
}
.ui-content {
	padding: 0px;
}

#featured-movies {
	width:100%;
	height:300px;
	margin: 0px;
}

#carousel-movies {
	width:320px;
	height:280px;

	float:left;
	position:relative;	/* On older OS versions "position" and "z-index" must be defined, */
	z-index:1;			/* it seems that recent webkit is less picky and works anyway. */
	overflow:hidden;

	background:#aaa;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	-o-border-radius:10px;
	border-radius:10px;
	background:#e3e3e3;
}

.scroller {
	width:1600px;
	height:100%;
	float:left;
	padding:0;
}

.scroller ul {
	list-style:none;
	display:block;
	float:left;
	width:100%;
	height:100%;
	padding:0;
	margin:0;
	text-align:left;
}

.scroller li {
	-webkit-box-sizing:border-box;
	-moz-box-sizing:border-box;
	-o-box-sizing:border-box;
	box-sizing:border-box;
	display:block; float:left;
	width:320px; height:280px;
	/*width:60px; height:88px;*/
	text-align:center;
	font-family:georgia;
	font-size:10px;
	line-height:140%;
}

#nav {
	width:320px;
	float:left;
}

#prev, #next {
	float:left;
	font-weight:bold;
	font-size:14px;
	padding:5px 0;
	width:80px;
}

#next {
	float:right;
	text-align:right;
}


#indicator, #indicator > li {
	display:block; float:left;
	list-style:none;
	padding:0; margin:0;
}

#indicator {
	width:110px;
	padding:12px 0 0 30px;
}

#indicator > li {
	text-indent:-9999em;
	width:8px; height:8px;
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	-o-border-radius:4px;
	border-radius:4px;
	background:#ddd;
	overflow:hidden;
	margin-right:4px;
}

#indicator > li.active {
	background:#888;
}

#indicator > li:last-child {
	margin:0;
}
</style>

<div data-role="page" class="type-home">  

	<div data-role="header">
		<h1><?php echo $title; ?></h1>
	</div>  

	<div data-role="content">  
		<!-- carousel -->
		<section id="featured-movies" class="subtitle">
			<h2>Featured</h2>
			<div id="carousel-movies">
				<div class="scroller">
					<ul id="thelist">
					<?php
						for($i = 0; $i < 5; $i++) 
						{
							$file = 'content/images/poster'.($i+1).'.jpg';
							$url = base_url($file);
							echo '<li><a href="#"><img src="'.$url.'" /></a></li>';
						}
					?>
					</ul>
				</div>
			</div>
		</section>
		<!--
		<div id="nav">
			<div id="prev" onclick="movieScroll.scrollToPage('prev', 0);return false">&larr; prev</div>
			<ul id="indicator">
				<li class="active">1</li>
				<li>2</li>
				<li>3</li>
				<li>4</li>
				<li>5</li>
			</ul>
			<div id="next" onclick="movieScroll.scrollToPage('next', 0);return false">next &rarr;</div>
		</div>
		-->

		<section id="openning" class="subtitle">
			<h2>OPENNING</h2>
			<div id="wrapper-openning">
				<div id="scroller-openning" class="scroller">
					<ul id="thelist-openning">
						<?php
						for($i = 0; $i < 10; $i++) 
						{
							$file = 'content/images/poster'.($i+1).'.jpg';
							$url = base_url($file);
						?>
						<li style="width:60px; height:110px;">
							<div class="container-cell">
								<div class="poster">
									<a href="#"><img src="<?php echo $url ?>" width="60px" height="88px"/></a>
								</div>
								<span style="color=#000"><?php echo ($i+1) ?></span> 
							</div>
						</li>
						<?php 
						} ?>
					</ul>
				</div>
			</div>
		</section>
		
		<!--
		<div id="wrapper" style="height:100px;">
			<ul data-role="listview">
			<?php
				for($i = 0; $i < 20; $i++) 
				{
					echo '<li>item '.$i.'</li>';
				}
			?>
			</ul>
		</div>
		-->

		<!--
		<section id="featured-movies">
			<h2>Featured</h2>
			<div id="wrapper-movies" class="wrapper">
				<div class="scroller">
					<div class="container-cell">
						<div class="container-poster">
							<a href="#"><img src="<?php echo base_url('content/images/poster1.jpg')?>" align="center" /></a>
						</div>
						<span>Movie #1<span>
					</div>
				</div>
			</div>
		</section>
		-->
	</div>
	
	<div data-role="footer">
			<h4>&copy; WebAppDev.net</h4>
	</div>	
</div>
