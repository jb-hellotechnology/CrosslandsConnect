<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('secrets.php');
include('layouts/header.php');
if(!$session){
	header('location:/');
	exit();
}
if(!$crosslands->has_relationship($contact['CONTACT_ID'], '23581')){
	header('location:/');	
}
?>
	<main>
		<article>
			<h1>Cultivate <?php if($crosslands->has_tag($contact['CONTACT_ID'], 'cultivate-tutor')){ echo '<span>Tutor</span>';}else{echo '<span>Student</span>';}?></h1>
			<div class="tabs-container">
				<ul class="tabs">
					<li class="active" data-tab="reminders">Reminders</li>
					<li data-tab="links">Links</li>
					<li data-tab="cohort">Cohort</li>
				</ul>
				<div class="tab active reminders">		
					<h4>Reminders</h4>
					<?php
					if($crosslands->has_tag($contact['CONTACT_ID'], 'cultivate-tutor')){
						echo "<p>Student reading deadlines and tutorial details can be found below. Click the button to save the tutorial details to your calendar.</p>";
					}else{
						echo "<p>Your reading deadlines and tutorial details can be found below. Click the button to save the tutorial details to your calendar.</p>";
					}
					?>
					<?php $crosslands->cultivate_months(); ?>
				</div>
				<div class="tab links">
					<h4>Useful Links</h4>
					<p>Find links to helpful resources below.</p>
					<a class="button" href="https://www.perlego.com/login" target="_blank">Perlego</a>
					<a class="button" href="https://learn.crosslands.training/" target="_blank">Learning Platform</a>
				</div>
				<div class="tab cohort">
					<?php
					if($crosslands->has_tag($contact['CONTACT_ID'], 'cultivate-tutor')){
					?>
						<h4>Cohort Contact Details</h4>
						<p>Get in touch with your cohort using the information below.</p>
						<?php 
						$cohorts = $crosslands->cultivate_cohorts();
						while($tag = mysqli_fetch_assoc($cohorts)) {
							echo "<h2>".ucwords(str_replace("-", " ", $tag['TAG_NAME']))."</h2>";
							$crosslands->cultivate_cohort('tutor', $tag['TAG_NAME']); 	
						}
						?>
					<?php
					}else{
					?>
						<h4>Cohort Contact Details</h4>
						<p>Get in touch with your cohort using the information below. You can choose what to share publicly via your <a href="/profile">profile</a>.</p>
						<?php 
						$cohorts = $crosslands->cultivate_cohorts();
						while($tag = mysqli_fetch_assoc($cohorts)) {
							//echo "<h2>".ucwords(str_replace("-", " ", $tag['TAG_NAME']))."</h2>";
							if($crosslands->has_tag($contact['CONTACT_ID'], $tag['TAG_NAME'])){
								$crosslands->cultivate_cohort('student', $tag['TAG_NAME']); 	
							}
						}
						?>
					<?php
					}
					?>	
				</div>
			</div>
			<h2 class="news">Cultivate Articles</h2>
			<?php $crosslands->news(69) ?>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>