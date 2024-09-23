<?php

require_once('bdd.php');

if (isset($_POST['delete']) && isset($_POST['id'])){
	
	$id = $_POST['id'];
	
	$sql = "DELETE FROM events WHERE id = :id";
	$query = $bdd->prepare($sql);
	$query->bindParam(':id', $id);
	if ($query == false) {
		print_r($bdd->errorInfo());
		die ('Error loading data');
	}
	$res = $query->execute();
	if ($res == false) {
		print_r($query->errorInfo());
		die ('Error deleting data');
	}
	
}elseif (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['color']) && isset($_POST['id']) && isset($_POST['privacy'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $color = $_POST['color'];
    $id = $_POST['id'];
    $privacy = $_POST['privacy'];

    $sql = "UPDATE events SET title = :title, description = :description, color = :color, privacy = :privacy WHERE id = :id";
    $query = $bdd->prepare($sql);
    $query->bindParam(':title', $title);
    $query->bindParam(':description', $description);
    $query->bindParam(':color', $color);
    $query->bindParam(':privacy', $privacy);
    $query->bindParam(':id', $id);
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

header('Location: index.php');

?>
