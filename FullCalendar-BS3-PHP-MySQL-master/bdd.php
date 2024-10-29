<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=u626486614_helphub;charset=utf8', 'u626486614_HelpHubAdmin', '0#e9Tu?|Y2hS');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
