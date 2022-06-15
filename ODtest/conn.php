<?php
function getConnection()
{
	/* Revise below to meet your actual need. */
	$host = 'localhost';
	$dbname = 'odsky';
	$username = 'thdl';
	$password = 'thdl';

	/* Unless necessary, do not revise below. */
	$dbms = 'mysql';
	$charset = 'utf8mb4'; // To support Chinese

	/* Never revise below. */
	$dsn = "$dbms:host=$host;dbname=$dbname;charset=$charset";
	$options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	];

	return new PDO($dsn, $username, $password, $options);
}
