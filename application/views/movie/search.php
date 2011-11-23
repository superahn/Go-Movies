<div data-role="page">  

	<div data-role="header">
		<h1><?php echo $title; ?></h1>
	</div>  

	<div data-role="content">  
		
		<form action="<?php echo site_url('movies/search_response')?>" method="get" validation="required">

			<p>Type in the title of the movie</p>
			<input type="text" class="search_box" id="search_box" name="title">	
			<input type="submit" class="search_button" value="Search">
			
		</form>

		<div id="validation_error"></div>
	</div>
	
	<div data-role="footer">
			<h4>&copy; WebAppDev.net</h4>
	</div>
	
</div>

<script type="text/javascript">
	$('form[validation="required"]').submit(function() {
		if($('input:text[name=title]').val() == "") {
			$('#validation_error').html("<em>Title field is required.</em>");
			return false;
		} else {
			$('#validation_error').html("");
		}
	});
</script>

