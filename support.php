<?php
include('secrets.php');
include('layouts/header.php');
if(!$session){
	header('/');
	exit();
}
?>
	<main>
		<article>
			<h1>Get Support</h1>
			<p>Please use the form below to contact the Crosslands Team.</p>
			<div style="width:100%;height:500px;background:#f1f2f2;border-radius:10px;" data-fillout-id="kmjyo19xbxus" data-fillout-embed-type="standard" data-fillout-inherit-parameters data-fillout-dynamic-resize></div><script src="https://server.fillout.com/embed/v1/"></script>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>