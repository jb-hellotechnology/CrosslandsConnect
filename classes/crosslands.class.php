<?php

class Crosslands{
	
	protected $database;
	
	public function __construct(database $database) {
		$this->database = $database;
	}
   
   // Methods
   function get_contact($string) {
	if(is_numeric($string)){
		$query = "SELECT * FROM contacts WHERE CONTACT_ID='".$string."'";
	}else{
		$query = "SELECT * FROM contacts WHERE EMAIL_ADDRESS='".$string."'";	
	}
	
	$params = array();
	$result = $this->database->query($query, $params);
	
	if($result){
	  return mysqli_fetch_assoc($result);
	}else{
	  return false;
	}
  }
  
  function get_contact_profile($apiKey, $apiURL, $email) {
	  $query = "SELECT * FROM contacts WHERE EMAIL_ADDRESS='".$email."'";
	  $params = array();
	  $result = $this->database->query($query, $params);

	  $data = mysqli_fetch_assoc($result);
	  
	  $url = $apiURL.'Contacts/'.$data['CONTACT_ID'];
	  
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	  curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
	  $response = curl_exec($ch);
	  $contact = json_decode($response, true);
	  curl_close($ch);

	  if (curl_errno($ch)) {
		  echo 'Error:' . curl_error($ch);
	  }
	  curl_close($ch);
	  
	  return $contact;
	}
  
  function create_contact($apiKey, $apiURL, $email, $first_name, $last_name){
	
	$url = $apiURL.'Contacts';
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"FIRST_NAME\":\"".$first_name."\", \"LAST_NAME\":\"".$last_name."\", \"EMAIL_ADDRESS\":\"".$email."\", \"TAGS\":[{\"TAG_NAME\": \"portal\"}]}");
	
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$result = curl_exec($ch);
	$contact = json_decode($result, true);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	
	$sql = "INSERT INTO contacts (CONTACT_ID, FIRST_NAME, LAST_NAME, EMAIL_ADDRESS)
	VALUES ('".$contact['CONTACT_ID']."', '".$contact['FIRST_NAME']."', '".$contact['LAST_NAME']."', '".$contact['EMAIL_ADDRESS']."')";
	$params = array();
	$result = $this->database->query($sql, $params);
	
  }
  
  function get_organisation($organisation_id) {
	  $query = "SELECT * FROM organisations WHERE ORGANISATION_ID='".$organisation_id."'";
	  $params = array();
	  $result = $this->database->query($query, $params);
	  
	  if($result){
		return mysqli_fetch_assoc($result);
	  }else{
		return false;
	  }
	}
	
  function organisation_profile($organisation_id, $contact_id) {
	$query = "SELECT * FROM organisations WHERE ORGANISATION_ID='".$organisation_id."'";
	$params = array();
	$result = $this->database->query($query, $params);
	
	if($result->num_rows == 1){
	  $data = mysqli_fetch_assoc($result);
	  echo '<h2>My Church</h2>';
	  echo '<h3>'.$data['ORGANISATION_NAME'].'</h3>';
	  echo '<ul class="profile">';
	  if($data['ADDRESS_BILLING_STREET']){
		  echo '<li>
		  <span class="material-symbols-outlined">
		  location_on
		  </span><div>';
		  if($data['ADDRESS_BILLING_STREET']){echo $data['ADDRESS_BILLING_STREET'].'<br />';}
		  if($data['ADDRESS_BILLING_CITY']){echo $data['ADDRESS_BILLING_CITY'].'<br />';}
		  if($data['ADDRESS_BILLING_STATE']){echo $data['ADDRESS_BILLING_STATE'].'<br />';}
		  if($data['ADDRESS_BILLING_POSTCODE']){echo $data['ADDRESS_BILLING_POSTCODE'].'<br />';}
		  if($data['ADDRESS_BILLING_COUNTRY']){echo $data['ADDRESS_BILLING_COUNTRY'];}
		  echo '</div></li>';
	  }
	  if($data['PHONE']){
		echo '<li>
		<span class="material-symbols-outlined">
		call
		</span><div>';
		echo $data['PHONE'];
		echo '</div></li>';
	  }
	  if($data['WEBSITE']){
		  echo '<li>
		  <span class="material-symbols-outlined">
		  web_asset
		  </span><div>';
		  echo '<a href="'.$data['WEBSITE'].'" target="_blank">'.$data['WEBSITE'].'</a>';
		  echo '</div></li>';
		}
	  echo '</ul>';
	  print_r($data);
	  if($data['PRIMARY_CONTACT']==$contact_id){
		  echo '<p><strong>You are the primary contact for your church.</strong></p>';
	  }
	}
  }
  
  function news($category){
	  
	  if($category){
		$query = "SELECT * FROM news WHERE categories=$category ORDER BY date DESC LIMIT 4";  
	  }else{
		$query = "SELECT * FROM news ORDER BY date DESC LIMIT 4";  
	  }
	  
	  $params = array();
	  $result = $this->database->query($query, $params);
	  
	  echo '<ul class="cards slick">';
	  while($news = mysqli_fetch_assoc($result)) {
		  echo '<li>
		  <div class="card">
		  	<img src="'.$news['media'].'" alt="'.$news['title'].'" />
		  	<h3>'.$news['title'].'</h3>'.$news['excerpt'].'
			<a href="'.$news['link'].'" class="button" target="_blank">Read More</a>
		  </div>
		  </li>';
	  }
	  echo '</ul>';
  }
  
  function directory($contact_id, $organisation_id){
		
		$query = "SELECT * FROM contacts WHERE CONTACT_ID!=".$contact_id." AND (ORGANISATION_ID=".$organisation_id." AND directory=1) ORDER BY LAST_NAME DESC";
		$query = "SELECT * FROM contacts WHERE (ORGANISATION_ID=".$organisation_id." AND directory=1) ORDER BY LAST_NAME DESC";
		$params = array();
		$result = $this->database->query($query, $params);
		
		if($result->num_rows > 0){
		
			echo '<h3>Contacts Directory</h3>';
			echo '<ul class="contacts">';
			while($contact = mysqli_fetch_assoc($result)) {
				echo '<li>
				<span class="material-symbols-outlined">
				person
				</span>
				<div>'.$contact['FIRST_NAME'].' '.$contact['LAST_NAME'].'<br />';
				if($contact['PHONE']){ echo  $contact['PHONE'].'<br />'; }
				if($contact['EMAIL_ADDRESS']){ echo '<a href="mailto:$contact[EMAIL_ADDRESS]">'.$contact['EMAIL_ADDRESS'].'</a><br />';}
				echo '</div></li>';
			}
			echo '</ul>';
			
		}
	}
	
	function tags_update($contact_id){
		
		$timestamp = date("Y-m-d H:i:s");
		$query = "UPDATE contacts SET tags_updated='".$timestamp."' WHERE CONTACT_ID=".$contact_id;
		$params = array();
		$result = $this->database->query($query, $params);
		
	}
	
	function tags_updated($contact_id){
		
		$timestamp = date("Y-m-d H:i:s");
		$query = "SELECT tags_updated FROM contacts WHERE CONTACT_ID=".$contact_id;
		$params = array();
		$result = $this->database->query($query, $params);
		$data = mysqli_fetch_assoc($result);
		return $data['tags_updated'];
		
	}
	
	function has_tag($contact_id, $tag){
		
		$query = "SELECT * FROM tags WHERE CONTACT_ID='".$contact_id."' AND TAG_NAME='".$tag."'";
		$params = array();
		$result = $this->database->query($query, $params);
		if($result->num_rows > 0){
			return true;	
		}else{
			return false;
		}
		
	}
	
	function relationships_updated($contact_id){
		
		$timestamp = date("Y-m-d H:i:s");
		$query = "SELECT relationships_updated FROM contacts WHERE CONTACT_ID=".$contact_id;
		$params = array();
		$result = $this->database->query($query, $params);
		$data = mysqli_fetch_assoc($result);
		return $data['relationships_updated'];
		
	}
	
	function has_relationship($contact_id, $relationship){
		
		$query = "SELECT * FROM contact_relationships WHERE (LINK_OBJECT_ID='".$contact_id."' OR CONTACT_ID='".$contact_id."') AND RELATIONSHIP_ID='".$relationship."'";
		$params = array();
		$result = $this->database->query($query, $params);
		if($result->num_rows > 0){
			return true;	
		}else{
			return false;
		}
		
	}
	
	function mentees($contact_id){
		
		$query = "SELECT * FROM contact_relationships WHERE CONTACT_ID='".$contact_id."' AND RELATIONSHIP_ID='23337' AND IS_FORWARD='0'";
		$params = array();
		$result = $this->database->query($query, $params);
		return $result;
		
	}
	
	function mentors($contact_id){
		
		$query = "SELECT * FROM contact_relationships WHERE CONTACT_ID='".$contact_id."' AND RELATIONSHIP_ID='23337'";
		$params = array();
		$result = $this->database->query($query, $params);
		return $result;
		
	}
	
	function is_mentor($mentor, $mentee){
		
		$query = "SELECT * FROM contact_relationships WHERE CONTACT_ID='".$mentor."' AND LINK_OBJECT_ID='".$mentee."' AND RELATIONSHIP_ID='23337'";
		$params = array();
		$result = $this->database->query($query, $params);
		return $result->num_rows;
		
	}
	
	function cultivate_months(){
		
		$timestamp = date('Y-m-d H:i:s');
		$query = "SELECT * FROM cultivate_months WHERE deadline>='".$timestamp."'";
		$params = array();
		$result = $this->database->query($query, $params);
		if($result->num_rows > 0){
			echo '<ul class="reminders">';
			while($month = mysqli_fetch_assoc($result)) {
				$reading_deadline = date('d/m/Y', strtotime($month['deadline']));
				$tutorial_date = date('d/m/Y H:i', strtotime($month['tutorial_date']));
				$cal_date = date('Ymd\THis\Z', strtotime($month['tutorial_date']));
				echo '<li><h3>'.$month['RECORD_NAME'].'<time datetime="'.$month['deadline'].'"><a class="button" href="http://www.google.com/calendar/event?action=TEMPLATE&text=Cultivate - '.$month['RECORD_NAME'].'&dates='.$cal_date.'/'.$cal_date.'&details='.$month['reading'].'&location='.urlencode($month['tutorial_link']).'" target="_blank"><strong>Reading Deadline:</strong> '.$reading_deadline.'</time></a></h3><p>'.$month['reading'].'</p>
				<a class="button" href="http://www.google.com/calendar/event?action=TEMPLATE&text=Cultivate - '.$month['RECORD_NAME'].'&dates='.$cal_date.'/'.$cal_date.'&details='.$month['reading'].'&location='.urlencode($month['tutorial_link']).'" target="_blank"><strong>Tutorial: <em>'.$tutorial_date.'</em></strong> <span class="material-symbols-outlined">
				calendar_add_on
				</span></a></li>';
			}
			echo '</ul>';
		}else{
			echo '<p><em>No deadlines pending.</em></p>';
		}
		
	}
	
	function cultivate_cohorts(){
		$query = "SELECT DISTINCT TAG_NAME FROM tags WHERE LEFT (TAG_NAME, 10)='cultivate-' AND TAG_NAME!='cultivate-tutor'";
		$params = array();
		$result = $this->database->query($query, $params);
		return $result;
	}
	
	function cultivate_cohort($type, $tag){
	
		//$query = "SELECT DISTINCT LINK_OBJECT_ID as CONTACT_ID FROM `contact_relationships` WHERE RELATIONSHIP_ID='23581' AND IS_FORWARD=0";
		$query = "SELECT DISTINCT contact_relationships.LINK_OBJECT_ID as CONTACT_ID FROM `contact_relationships`, tags WHERE (contact_relationships.RELATIONSHIP_ID='23581' AND contact_relationships.IS_FORWARD=0) AND (tags.CONTACT_ID=contact_relationships.LINK_OBJECT_ID AND tags.TAG_NAME='".$tag."')";
		$params = array();
		$result = $this->database->query($query, $params);
		
		if($result->num_rows > 0){
			echo '<ul class="contacts">';
			while($contact = mysqli_fetch_assoc($result)) {
				$query = "SELECT * FROM contacts WHERE CONTACT_ID='".$contact['CONTACT_ID']."'";
				$params = array();
				$result2 = $this->database->query($query, $params);
				$data = mysqli_fetch_assoc($result2);
				echo '<li>';
						if(file_exists('./uploads/'.$data['CONTACT_ID'].'.jpg')){
							echo '<img src="./uploads/'.$data['CONTACT_ID'].'.jpg?v='.rand().'" alt="Profile" />';
						}
						echo '<h4>'.$data['FIRST_NAME'].' '.$data['LAST_NAME'].'</h4>';
						if($data['bio']){ echo '<p>'.$data['bio'].'</p>';}
						
						echo '<ul class="contact-details">';
						if($data['share_phone'] OR $type=='tutor'){
							echo '<li><span class="material-symbols-outlined">smartphone</span><a href="tel:'.$data['PHONE'].'">'.$data['PHONE'].'</a></li>';
						}
						if($data['share_email'] OR $type=='tutor'){
							echo '<li><span class="material-symbols-outlined">mail</span><a href="mailto:'.$data['EMAIL_ADDRESS'].'">'.$data['EMAIL_ADDRESS'].'</a></li>';
						}
						echo '</ul>';
				echo '</li>';
			}
			echo '</ul>';
		}else{
			echo '<p><em>No contacts.</em></p>';
		}
		
	}
	
}