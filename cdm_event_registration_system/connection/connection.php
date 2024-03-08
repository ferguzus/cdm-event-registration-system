<?php

function connection() {
	$hostName = "localhost";
	$dbUser = "root";
	$dbPassword = "12345";
	$dbName = "cdm_event_registration_system";

	$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	return $conn;
}
?>