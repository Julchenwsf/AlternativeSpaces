<?php
include("DBConnection.php");
$id = $_GET['id'];
//delete record based on selected id from deleteusers.php
		$query = "Delete from users where id=$id";
		mysql_query($query);
		header("Location: DBUsers.php");
?>
