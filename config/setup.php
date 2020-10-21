<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   setup.php                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/05 18:31:17 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/05 18:31:17 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

function connect()
{
	require "config/database.php";
	$dsn = preg_replace('/;dbname=([^;]+);/', ';', $DB_DSN);
	$pdo = new PDO($dsn, $DB_USER, $DB_PASSWORD, $DB_OPTIONS);
	return $pdo;
}

function create()
{
	require "config/database.php";
	$pdo = connect();
	$sql = "CREATE DATABASE IF NOT EXISTS $DB_DBNAME";
	$pdo->prepare($sql)->execute();
	$sql = "CREATE TABLE IF NOT EXISTS $DB_DBNAME.users
	(
		id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
		username VARCHAR(255) UNIQUE NOT NULL,
		email VARCHAR(255) UNIQUE NOT NULL,
		password VARCHAR(255) NOT NULL,
		email_confirmed BOOLEAN default false
	);";
	$pdo->prepare($sql)->execute();
	echo "DB Schema created" . PHP_EOL;

}

function destroy()
{
	require "config/database.php";
	$pdo = connect();
	$sql = "DROP DATABASE IF EXISTS $DB_DBNAME";
	$pdo->prepare($sql)->execute();
	echo "DB Schema destroyed" . PHP_EOL;

}

try {
	if ($argc === 1 || $argv[1] === "-i") {
		destroy();
		create();
	} else if ($argc === 2 && $argv[1] === "-d") {
		destroy();
	} else if ($argc === 2 && $argv[1] === "-r") {
		destroy();
		create();
	}
} catch (Exception $e) {
	echo $e->getMessage() . PHP_EOL;
}


?>
