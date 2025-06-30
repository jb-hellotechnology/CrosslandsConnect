<?php
include('secrets.php');
include('layouts/header.php');
if(!$session){
	header('location:/');
	exit();
}
$mentee = $_GET['mentee'];
if(!$crosslands->is_mentor($contact['CONTACT_ID'], $mentee)){
	header('location:/');	
}

$mentee = $crosslands->get_contact($mentee);
?>
	<main>
		<article>
			<h1><?= $mentee['FIRST_NAME'] ?> <?= $mentee['LAST_NAME'] ?> <span>Mentee</span></h1>
			<div class="tabs-container">
				<ul class="tabs">
					<?php
					if($crosslands->has_relationship($contact['CONTACT_ID'], '23581')){
					?>
					<li class="active" data-tab="reminders">Cultivate</li>
					<?php
					}
					?>
				</ul>
				<div class="tab active reminders">		
					<h4>Reminders</h4>
					<p>Your Mentee's reading deadlines and tutorial details can be found below.</p>		
					<?php $crosslands->cultivate_months(); ?>
				</div>
			</div>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>