<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Database.class.php                                 :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/18 18:45:53 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/18 18:45:53 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class Database
{
	private $_pdo;
	private $_statement;


	public function __construct()
	{
		require_once "config/database.php";
		$this->_pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPTIONS);
	}

	public function prepare($sql)
	{
		$this->_statement = $this->_pdo->prepare($sql);
	}

	public function bindValue($param, $value)
	{
		$type;

		switch (true) {
			case is_int($value):
				$type = PDO::PARAM_INT;
				break;
			case is_bool($value):
				$type = PDO::PARAM_BOOL;
				break;
			default:
				$type = PDO::PARAM_STR;
		}
		$this->_statement->bindValue($param, $value, $type);
	}

	public function execute()
	{
		$this->_statement->execute();
		return $this->_pdo->lastInsertId();
	}

	public function fetch($class = null)
	{
		$this->_statement->execute();
		if ($class) {
			return $this->_statement->fetchObject($class);
		}
		return $this->_statement->fetch();
	}

	public function fetchAll($class = null)
	{
		$this->_statement->execute();
		if ($class) {
			return $this->_statement->fetchAll(PDO::FETCH_CLASS, $class);
		}
		return $this->_statement->fetchAll();
	}
}
