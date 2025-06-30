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
			echo '<h1>My Profile</h1>';
			echo '<p>Manage your Crosslands profile, update your contact details and manage your communciation preferences.</p>';
			echo '<div class="accordion">';
			echo '<h3><span>&plus;</span> Contact Details</h3>';
			echo '<div>';
				// GET INSIGHTLY PROFILE
				$user = $crosslands->get_contact_profile($apiKey, $apiURL, $session->user['email']);
				$userLocal = $crosslands->get_contact($session->user['email']);
				
				if($user['FIRST_NAME']=='UNKNOWN'){
					$user['FIRST_NAME']='';
				}
	
				echo '<form method="" id="details">';
				
				echo '<div class="input">';
				echo '<label for="first_name">First Name <span>required</span></label>';
				echo '<input type="text" id="first_name" name="first_name" value="'.$user['FIRST_NAME'].'" required />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="last_name">Last Name <span>required</span></label>';
				echo '<input type="text" id="last_name" name="last_name" value="'.$user['LAST_NAME'].'" required />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="phone">Phone</label>';
				echo '<input type="text" id="phone" name="phone" value="'.$user['PHONE'].'" />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="address_mail_street">Address Street</label>';
				echo '<input type="text" id="address_name_street" name="address_mail_street" value="'.$user['ADDRESS_MAIL_STREET'].'" />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="address_mail_city">Address City</label>';
				echo '<input type="text" id="address_name_city" name="address_mail_city" value="'.$user['ADDRESS_MAIL_CITY'].'" />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="address_mail_state">Address County</label>';
				echo '<input type="text" id="address_name_state" name="address_mail_state" value="'.$user['ADDRESS_MAIL_STATE'].'" />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="address_mail_postcode">Address Post Code</label>';
				echo '<input type="text" id="address_name_postcode" name="address_mail_postcode" value="'.$user['ADDRESS_MAIL_POSTCODE'].'" />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="address_mail_country">Country</label>';
				echo '<input type="text" id="address_name_country" name="address_mail_country" value="'.$user['ADDRESS_MAIL_COUNTRY'].'" />';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="email_address">Email Address</label>';
				echo '<input type="text" id="email_address" name="email_address" value="'.$user['EMAIL_ADDRESS'].'" disabled />';
				echo '<p class="help">Need to update your email? Please <a href="https://crosslands.training/connect/contact/" target="_blank">contact us</a>.</p>';
				echo '</div>';
				
				echo '<div class="result details"></div>';
				
				echo '<div class="input submit">';
				echo '<input type="submit" value="Update Details" />';
				echo '</div>';
				
				echo '<input type="hidden" name="contact_id" value="'.$user['CONTACT_ID'].'" />';
				echo '</form>';
			
			echo '</div>';
			
			echo '<h3><span>&plus;</span> Profile</h3>';
			echo '<div>';
			
				echo '<p>These details will be visible to other Crosslands students to facilitate communication within your tutor group or cohort.</p>';
				
				echo '<form method="" id="profile">';
				
				echo '<div class="input">';
				echo '<label for="biography">Short Biography</label>';
				echo '<textarea id="biography" name="biography">'.$userLocal['bio'].'</textarea>';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="photo">Photo</label>';
				echo '<input type="file" id="photo" name="photo" />';
				echo '<div id="image">';
				if(file_exists('./uploads/'.$user['CONTACT_ID'].'.jpg')){
					echo '<img src="'.'/uploads/'.$user['CONTACT_ID'].'.jpg?v='.rand().'" alt="Profile Image" />';
				}
				echo '</div>';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="share_email"><input type="checkbox" name="share_email" id="share_email" value="1"'; if($userLocal['share_email']==1){echo ' CHECKED';} echo ' /> I&rsquo;m happy to share my email</label>';
				echo '</div>';
				
				echo '<div class="input">';
				echo '<label for="share_phone"><input type="checkbox" name="share_phone" id="share_phone" value="1"'; if($userLocal['share_phone']==1){echo ' CHECKED';} echo ' /> I&rsquo;m happy to share my phone number</label>';
				echo '</div>';
				
				echo '<div class="result profile"></div>';
				
				echo '<div class="input submit">';
				echo '<input type="submit" value="Update Profile" />';
				echo '</div>';
				
				echo '<input type="hidden" name="contact_id" value="'.$user['CONTACT_ID'].'" />';
				echo '</form>';
			
			echo '</div>';
			
			// echo '<h3><span>&plus;</span> Email Preferences</h3>';
			// 
			// echo '<div>';
			// 
			// 	echo '<p>Opt in and out of our email lists.</p>';
			// 	echo '<p><em>Not built yet.</em></p>';
			// 
			// echo '</div>';
			?>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>