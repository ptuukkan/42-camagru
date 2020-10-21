<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Session.class.php                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/21 22:20:01 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/21 22:20:01 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class Session
{
	public static $verbose = false;
	private $_userId = null;
	private $_emailConfirmed = false;
	public $loggedIn = false;

	public function __construct()
	{
		session_start();
		$this->loadSession();
		if ($this->_userId) {
			$this->loggedIn = true;
		}
		if (self::$verbose) {
			print(static::class . " instance constructed" . PHP_EOL);
		}
	}

	public function loadSession()
	{
		$this->_userId = $_SESSION["logged_on_user"]["userid"] ?? "";
		$this->_emailConfirmed = $_SESSION["logged_on_user"]["email_confirmed"] ?? false;
	}

	public function setSession($userId, $emailConfirmed)
	{
		$this->_userId = $userId;
		$this->_emailConfirmed = $emailConfirmed;
		$this->loggedIn = true;
		$_SESSION["logged_on_user"]["userid"] = $userId;
		$_SESSION["logged_on_user"]["email_confirmed"] = $emailConfirmed;
	}

	public function __toString()
	{
		$str = "Session(" . PHP_EOL;
		$str .= "userId: " . $this->_userId . PHP_EOL;
		$str .= "emailConfirmed: " . $this->_emailConfirmed . PHP_EOL;
		$str .= ")";
		return $str;
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print(static::class . " instance destructed" . PHP_EOL);
		}
	}
}
