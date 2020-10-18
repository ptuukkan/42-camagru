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

require_once "config/database.php";

function connect()
{
	$dsn = str_replace('/;dbname=([^;]+);/', ';', $DB_DSN);
	$pdo = new PDO($dsn, $DB_USER, $DB_PASSWORD, $DB_OPTIONS);
	return $pdo;
}

function create()
{
	$pdo = connect();
	$sql = "CREATE DATABASE IF NOT EXISTS $DB_DBNAME";
	$pdo->prepare($sql)->execute();
}

function destroy()
{

}

if ($argc === 1 || $argv[1] === "-i") {
	create();
} else if ($argc === 2 && $argv[1] === "-d") {
	destroy();
} else if ($argc === 2 && $argv[1] === "-r") {
	destroy();
	create();
}

?>
