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
	public $userId = null;
	public $loggedIn = false;

	public function __construct()
	{
		session_start();
		$this->loadSession();
		if ($this->userId) {
			$this->loggedIn = true;
		}
	}

	public function loadSession()
	{
		$this->userId = $_SESSION["logged_on_user"]["userid"] ?? null;
		if ($this->userId) {
			$this->loggedIn = true;
		}
	}

	public function setSession($userId)
	{
		$this->userId = $userId;;
		$this->loggedIn = true;
		$_SESSION["logged_on_user"]["userid"] = $userId;
	}

	public function logout()
	{
		$this->loggedIn = false;
		$this->userId = null;
		unset($_SESSION["logged_on_user"]);
	}
}
