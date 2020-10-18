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

	public function __construct()
	{
		require_once "config/database.php";
		$this->_pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPTIONS);
	}
}
