<?php

include('../secrets.php');

include('../classes/database.class.php');

$database = new Database("localhost", $dbuser, $dbpass, $dbname);

$q = strip_tags(addslashes($_POST['q']));
	
$sql = "SELECT * FROM organisations WHERE ORGANISATION_NAME LIKE '%".$q."%' LIMIT 10";
$params = array();
$result = $database->query($sql, $params);

if($result->num_rows > 0 AND $q!=='') {

	$rows = $result->fetch_all(MYSQLI_ASSOC);
	echo '<p><strong>Results:</strong></p>';
	echo '<ul>';
	foreach($rows as $data){
		echo '<li><strong><a href="#" class="connect" data-id="'.$data['ORGANISATION_ID'].'">'.$data['ORGANISATION_NAME'].'</a></strong><br />'.$data['ADDRESS_BILLING_STREET'].'</li>';
	}
	echo '</ul>';

}else if($q){
	echo '<p class="alert warning">No results found, please try a different query</p>';
}
?>