<?php

// Connexion à la base de données
require_once('bdd.php');
//echo $_POST['title'];
if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color']) && isset($_POST['privacy'])){
	
	$title = $_POST['title'];
	$start = $_POST['start'];
	$description = $_POST['description'];
	$end = $_POST['end'];
	$color = $_POST['color'];
	$privacy = $_POST['privacy'];

	$sql = "INSERT INTO events(title, description, start, end, color, privacy) values ('$title','$description', '$start', '$end', '$color', '$privacy')";
	//$req = $bdd->prepare($sql);
	//$req->execute();
	
	echo $sql;
	
	$query = $bdd->prepare( $sql );
	if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Erreur prepare');
	}
	$sth = $query->execute();
	if ($sth == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}

}
header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
