<?php

include('../secrets.php');
include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$url = 'https://crosslands.training/wp-json/wp/v2/posts';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
$response = curl_exec($ch);
$response = json_decode($response, true);
curl_close($ch);

foreach($response as $news){

	$sql = "SELECT * FROM news WHERE id=".$news['id'];
	$params = array();
	$result = $database->query($sql, $params);
	
	if ($result->num_rows > 0) {
		
		$row = $result->fetch_assoc();
		$url = 'https://crosslands.training/wp-json/wp/v2/media/'.$row['featured_media'];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
		$response2 = curl_exec($ch);
		$media = json_decode($response2, true);
		curl_close($ch);
		
		$categories = implode(",", $news['categories']);

		$sql = "UPDATE news SET date='".addslashes($news['date'])."', title='".addslashes($news['title']['rendered'])."', link='".$news['link']."', excerpt='".addslashes($news['excerpt']['rendered'])."', featured_media='".addslashes($news['featured_media'])."', media='".$media['media_details']['sizes']['large']['source_url']."', categories='".$categories."' WHERE id=".$news['id'];
		$params = array();
		
		if ($database->query($sql, $params) === TRUE) {
			echo "Record updated successfully<br />";
		} else {
			echo "Error updating record: " . $database->error;
		}
		
	} else {

		$url = 'https://crosslands.training/wp-json/wp/v2/media/'.$news['featured_media'];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");  
		$response2 = curl_exec($ch);
		$media = json_decode($response2, true);
		curl_close($ch);
		
		$categories = implode(",", $news['categories']);
		
		$sql = "INSERT INTO news (id, date, title, link, excerpt, featured_media, media, categories)
		VALUES ('".addslashes($news['id'])."', '".addslashes($news['date'])."', '".addslashes($news['title']['rendered'])."', '".addslashes($news['link'])."', '".addslashes($news['excerpt']['rendered'])."', '".addslashes($news['featured_media'])."', '".addslashes($media['media_details']['sizes']['large']['source_url'])."', '".$categories."')";
		$params = array();
		
		if ($database->query($sql, $params) === TRUE) {
			echo "New record created successfully<br />";
		} else {
			echo "Error: " . $sql . "<br>" . $database->error;
		}
		
	}

}
