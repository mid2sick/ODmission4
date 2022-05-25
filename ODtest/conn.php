<?php
function getConnection()
{
	$dbms = 'mysql';
	$host = 'localhost';
	$dbname = 'test'; // TODO need to revise
	$dsn = "$dbms:host=$host;dbname=$dbname;charset=utf8mb4"; // To support Chinese
	$username = 'root';  // TODO need to revise
	$password = null; // TODO need to revise
	$option = null;

	return new PDO($dsn, $username, $password, $option);
}
