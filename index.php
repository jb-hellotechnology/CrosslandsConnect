<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('secrets.php');
include('layouts/header.php');
?>
	<main <?php if ($session === null) {echo 'class="centered"';}?>>
		<article>
			<?php
			if ($session === null) {
				// The user isn't logged in.
				echo '<h1>Crosslands Connect</h1>';
				echo '<p>Access your learning platform or log in to Crosslands Connect:</p>';
				echo '<p><a class="button secondary" href="https://learn.crosslands.training/">Access Learning Platform</a></p>';
				echo '<p><a class="button" href="/process/login">Crosslands Connect</a></p>';
			}else{
				// The user is logged in.
				echo '<h1>Hi, ';if($contact['FIRST_NAME']!=='UNKNOWN'){echo $contact['FIRST_NAME'];} echo '</h1>';
				echo '<p>Welcome to Crosslands Connect. This is a work in progress, so things may not work as expected. Please use <a href="https://form.fillout.com/t/448Q71csoJeu" target="_blank">this form</a> to provide any feedback.</p>';
				
				// SHOW COURSES
				echo '<h2>My Programmes</h2>';
				echo '<ul class="courses">';
				$no_courses = true;
				if($crosslands->has_relationship($contact['CONTACT_ID'], '23581')){
					echo '<li><a href="/cultivate"><img src="/assets/images/cultivate.svg" alt="Cultivate" /><h3>Cultivate</h3><p>Nurturing fresh new voices – marked by biblical clarity, excellent insight, and personal humility – to speak into our culture.</p></a></li>';
					$no_courses = false;
				}
				echo '</ul>';
				if($no_courses){
					echo '<p><em>You&rsquo;re not signed up for any courses.</em></p>';
				}
				
				// MENTEE RELATIONSHIPS
				$mentees = $crosslands->mentees($contact['CONTACT_ID']);
				if($mentees->num_rows > 0){
					echo '<h2>My Mentees</h2>';
					echo '<ul class="contacts">';
					while($contact = mysqli_fetch_assoc($mentees)) {
						$data = $crosslands->get_contact($contact['LINK_OBJECT_ID']);
						echo '<li>';
								if(file_exists('./uploads/'.$data['CONTACT_ID'].'.jpg')){
									echo '<img src="./uploads/'.$data['CONTACT_ID'].'.jpg?v='.rand().'" alt="Profile" />';
								}
								echo '<h4><a href="/mentee/'.$contact['LINK_OBJECT_ID'].'">'.$data['FIRST_NAME'].' '.$data['LAST_NAME'].'</a></h4>';
								if($data['bio']){ echo '<p>'.$data['bio'].'</p>';}
								
								echo '<ul class="contact-details">';
								if($data['PHONE']){
									echo '<li><span class="material-symbols-outlined">smartphone</span><a href="tel:'.$data['PHONE'].'">'.$data['PHONE'].'</a></li>';
								}
								if($data['EMAIL_ADDRESS']){
									echo '<li><span class="material-symbols-outlined">mail</span><a href="mailto:'.$data['EMAIL_ADDRESS'].'">'.$data['EMAIL_ADDRESS'].'</a></li>';
								}
								echo '</ul>';
						echo '</li>';
					}
					echo '</ul>';
					echo "<h3>Other Tools for Mentors</h3>";
					echo '<p><a href="https://form.fillout.com/t/uhiGDpxyM4eu" class="button" target="_blank">Buy Perlego Subscription</a></p>';
				}
				
				// TUTOR RELATIONSHIPS
				$tutees = $crosslands->mentees($contact['CONTACT_ID']);
				if($tutees->num_rows > 0){
					echo '<h2>My Tutees</h2>';
					echo '<ul class="contacts">';
					while($contact = mysqli_fetch_assoc($tutees)) {
						$data = $crosslands->get_contact($contact['LINK_OBJECT_ID']);
						echo '<li>';
								if(file_exists('./uploads/'.$data['CONTACT_ID'].'.jpg')){
									echo '<img src="./uploads/'.$data['CONTACT_ID'].'.jpg?v='.rand().'" alt="Profile" />';
								}
								echo '<h4><a href="/mentee/'.$contact['LINK_OBJECT_ID'].'">'.$data['FIRST_NAME'].' '.$data['LAST_NAME'].'</a></h4>';
								if($data['bio']){ echo '<p>'.$data['bio'].'</p>';}
								
								echo '<ul class="contact-details">';
								if($data['PHONE']){
									echo '<li><span class="material-symbols-outlined">smartphone</span><a href="tel:'.$data['PHONE'].'">'.$data['PHONE'].'</a></li>';
								}
								if($data['EMAIL_ADDRESS']){
									echo '<li><span class="material-symbols-outlined">mail</span><a href="mailto:'.$data['EMAIL_ADDRESS'].'">'.$data['EMAIL_ADDRESS'].'</a></li>';
								}
								echo '</ul>';
						echo '</li>';
					}
					echo '</ul>';
					// echo "<h3>Other Tools for Mentors</h3>";
					// echo '<p><a href="https://form.fillout.com/t/uhiGDpxyM4eu" class="button" target="_blank">Buy Perlego Subscription</a></p>';
				}
				
				$mentors = $crosslands->mentors($contact['CONTACT_ID']);
				if($mentors->num_rows > 0){
					echo '<h2>My Mentor</h2>';
					echo '<ul class="contacts">';
					while($contact = mysqli_fetch_assoc($mentors)) {
						$data = $crosslands->get_contact($contact['LINK_OBJECT_ID']);
						echo '<li>';
								if(file_exists('./uploads/'.$data['CONTACT_ID'].'.jpg')){
									echo '<img src="./uploads/'.$data['CONTACT_ID'].'.jpg?v='.rand().'" alt="Profile" />';
								}
								echo '<h4>'.$data['FIRST_NAME'].' '.$data['LAST_NAME'].'</h4>';
								if($data['bio']){ echo '<p>'.$data['bio'].'</p>';}
								
								echo '<ul class="contact-details">';
								if($data['PHONE']){
									echo '<li><span class="material-symbols-outlined">smartphone</span><a href="tel:'.$data['PHONE'].'">'.$data['PHONE'].'</a></li>';
								}
								if($data['EMAIL_ADDRESS']){
									echo '<li><span class="material-symbols-outlined">mail</span><a href="mailto:'.$data['EMAIL_ADDRESS'].'">'.$data['EMAIL_ADDRESS'].'</a></li>';
								}
								echo '</ul>';
						echo '</li>';
					}
					echo '</ul>';
				}
				
				if($organisation){
					$crosslands->organisation_profile($organisation['ORGANISATION_ID'],$contact['CONTACT_ID']);
					//$crosslands->directory($contact['CONTACT_ID'], $organisation['ORGANISATION_ID']);
				} 
			}
			echo '<h2 class="news">Crosslands News</h2>';
			$crosslands->news(0);
			?>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>