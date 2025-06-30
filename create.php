<?php
include('secrets.php');
include('layouts/header.create.php');
if(!$session){
	header('/');
	exit();
}
?>
	<main>
		<article>
			<?php
			echo '<h1>Welcome</h1>';
			echo '<p>Please enter your name and click proceed to access the portal:</p>';
			
			echo '<form method="" id="user">';
			
			echo '<div class="input">';
			echo '<label for="first_name">First Name <span>required</span></label>';
			echo '<input type="text" id="first_name" name="first_name" required />';
			echo '</div>';
			
			echo '<div class="input">';
			echo '<label for="last_name">Last Name <span>required</span></label>';
			echo '<input type="text" id="last_name" name="last_name" required />';
			echo '</div>';
			
			echo '<div class="result user"></div>';
			
			echo '<div class="input submit">';
			echo '<input type="submit" value="Proceed" />';
			echo '</div>';

			echo '<input type="hidden" name="email" value="'.$session->user['email'].'" />';
			
			echo '</form>';
			?>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>