<?php 

	if (md5($_GET['password']) == md5('2Ddo6J2OQ5')) {
		
		$validated = true;

	}else{

		$validated = false;

	}
	
?>
<html>

<head>

<script type="text/javascript" src="../../assets/lib/jquery-2.1.3.min.js"></script>

</head>

<body>

<?php if (!$validated) { ?>
	
	<h2>503 Forbidden</h2>		

<?php } else { ?>	

	<h2> Welcome admin! this page currently looks shit, but anyways, just bear it in mind... </h2>

	<section>
		<h3>News management:</h3>

		<form id="post-form">

			<textarea name="post-content" id="post-content">New post...</textarea>

			<input type="hidden" name="action" value="post-announcement">

			<input type="submit">	
		
		</form>

	</section>

	<section>

		<h3>Subject options management:</h3>
		
	</section>

	<section>

		<h3>Some extra options:</h3>

		<button id="refresh-cache">Refresh cache</button> <br/>

		<button id="enter-maintanence">Enter maintanence</button>

		<button id="exit-maintanence">Exit mantanence</button>

	</section>
		
<?php } ?>

<script type="text/javascript" src="js/handler.js"></script>

</body>

</html>