<!doctype html>
<html>
<head>
	<title></title>
	<style>
	#msg {
		color: red;
	}
	</style>
	<script type = "text/javascript" src = "jquery-1.9.1.min.js"></script>
	<script type = "text/javascript">
	(function($){
		$(document).ready(function(){
			$('#upload').submit(function() {
				
				if($('#file').val().length > 0) {
					
				}else{
					$("#msg").text("Invalid file!").show().fadeOut(1000);
					return false;
				}
			});
				
		
		});
	})(jQuery);
	</script>
</head>
<body>
	<div id = "form-div">
		<span id = "msg"></span>
		<form id="upload" method="post" enctype="multipart/form-data" action = "process.php">
			<label for="file">Filename:</label>
			<input type="file" name="file" id="file" /> 
			<br>
			<input type = "hidden" name = "" />
			<input type="submit" name="submit" value="Upload file" id = "submit" />
		</form>

	</div>
</body>
</html>