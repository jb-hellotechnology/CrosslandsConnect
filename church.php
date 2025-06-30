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
			<?php
			echo '<h1>My Church</h1>';
			
			// GET INSIGHTLY PROFILE
			$user = $crosslands->get_contact_profile($apiKey, $apiURL, $session->user['email']);
			$userLocal = $crosslands->get_contact($session->user['email']);
			
			if($user['FIRST_NAME']=='UNKNOWN'){
				$user['FIRST_NAME']='';
			}

			if(!$user['ORGANISATION_ID']){
				echo '<p class="alert"><strong>You\'re not connected to a church</strong><br />Use the form below to search for your church.</p>';
				echo '<form method="post" id="organisation" class="single">';
				
				echo '<div class="input">';
				echo '<label for="q">Search</label>';
				echo '<input type="text" id="q" name="q" value="" placeholder="Chur..." />';
				echo '</div>';
				
				echo '<div id="organisations"></div>';
				
				echo '<div class="result organisations"></div>';
				
				echo '</form>';
				
				echo '<div id="organisation_connect"></div>';
				
				echo '<div id="connect_action"></div>';
				
				echo '<p>Can\'t find your church? Please <a href="https://crosslands.training/connect/contact/" target="_blank">contact us</a>.</p>';
			}else{
				$organisation = $crosslands->get_organisation($user['ORGANISATION_ID']);
				echo '<p class="alert success"><strong>You\'re connected with:</strong> '.$organisation['ORGANISATION_NAME'].'</p>';
				echo '<h3>Address</h3>';
				echo '<p>';
				if($organisation['ADDRESS_BILLING_STREET']){
					echo $organisation['ADDRESS_BILLING_STREET'].'<br />';
				}
				if($organisation['ADDRESS_BILLING_CITY']){
					echo $organisation['ADDRESS_BILLING_CITY'].'<br />';
				}
				if($organisation['ADDRESS_BILLING_STATE']){
					echo $organisation['ADDRESS_BILLING_STATE'].'<br />';
				}
				if($organisation['ADDRESS_BILLING_POSTCODE']){
					echo $organisation['ADDRESS_BILLING_POSTCODE'].'<br />';
				}
				if($organisation['ADDRESS_BILLING_COUNTRY']){
					echo $organisation['ADDRESS_BILLING_COUNTRY'].'<br />';
				}
				echo '</p>';
				
				if($organisation['WEBSITE'] OR $organisation['PHONE']){
					echo '<h3>Contact</h3>';
					echo '<p>';
					if($organisation['WEBSITE']){
						echo '<a href="'.$organisation['WEBSITE'].'" target="_blank">'.$organisation['WEBSITE'].'</a><br />';
					}
					if($organisation['PHONE']){
						echo $organisation['PHONE'].'<br />';
					}
					echo '</p>';
				}
				//echo '<p>Your name, phone number and email address will be visible to your church\'s primary contact.</p>';
// 				echo '<h3>Join Directory?</h3>';
// 				echo '<p>Please choose whether you would like your name, email address and phone number to be visible to others connect with '.$organisation['ORGANISATION_NAME'].' in the Crosslands Portal.</p>';
// 				echo '<form method="" id="directory" class="single">';
// 
// 				echo '<div class="input">';
// 				echo '<label for="yes"><input type="radio" name="directory" value="1" id="yes"'; if($userLocal['directory']==1){echo ' CHECKED';}echo '> Yes, my contact details can be made visible to others</label>';
// 				echo '<label for="no"><input type="radio" name="directory" value="0" id="no"'; if($userLocal['directory']!==1){echo ' CHECKED';}echo '> No, please do not make my contact details visible to others</label>';
// 				echo '</div>';
// 				
// 				echo '<div class="result directory"></div>';
// 				
// 				echo '<div class="input submit">';
// 				echo '<input type="submit" value="Update Preference" />';
// 				echo '</div>';
// 				
// 				echo '<input type="hidden" name="contact_id" value="'.$user['CONTACT_ID'].'" />';
// 				
// 				echo '</form>';
			}
			
			?>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>