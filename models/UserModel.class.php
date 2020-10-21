<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   UserModel.class.php                                :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/20 19:41:23 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/20 19:41:23 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "BaseModel.class.php";

class UserModel extends BaseModel
{
	private $errors = [];
	private $email = "";
	private $username = "";
	private $password = "";
	private $passwordConfirm = "";
	private $email_confirmed = false;

	protected function _tableName()
	{
		return "users";
	}

	protected function _propertiesInDb()
	{
		return ["email", "username", "password", "email_confirmed"];
	}

	public function setAttributes($params)
	{
		$this->email = $params["email"];
		$this->username = $params["username"];
		$this->password = $params["password"];
		$this->passwordConfirm = $params["password_confirm"];
	}

	private function _setError($attribute, $error)
	{
		$this->_errors[$attribute][] = $error;
	}

	private function _validateEmail()
	{
		$valid = filter_var($this->email, FILTER_VALIDATE_EMAIL);
		if (!$valid) {
			$this->_setError("email", "Email address is not valid");
		}
		if ($valid && self::findOne(["email" => $this->email])) {
			$this->_setError("email", "Email address is already in use");
		}
	}

	private function _validateUsername()
	{
		if (strlen($this->username) < 3) {
			$this->_setError("username", "Username must be at least 3 characters");
		}
		$valid = filter_var($this->username, FILTER_VALIDATE_REGEXP, [
			"options" => ["regexp" => "/^[a-zA-Z0-9]+$/"]
		]);
		if (!$valid) {
			$this->_setError("username", "Username must contain only alphanumeric characters");
		}
		if ($valid && self::findOne(["username" => $this->username])) {
			$this->_setError("username", "Username is already in use");
		}

	}

	private function _validatePassword()
	{
		if (strlen($this->password) < 8) {
			$this->_setError("password", "Password must be at least 8 characters");
		}
		if (!filter_var($this->password, FILTER_VALIDATE_REGEXP, [
			"options" => ["regexp" => "/[A-Z]/"]
		])) {
			$this->_setError("password", "Password must contain at least 1 uppercase character");
		}
		if (!filter_var($this->password, FILTER_VALIDATE_REGEXP, [
			"options" => ["regexp" => "/[0-9]/"]
		])) {
			$this->_setError("password", "Password must contain at least 1 number");
		}
	}

	private function _validatePwConfirm()
	{
		if ($this->password !== $this->passwordConfirm) {
			$this->_setError("password_confirm", "Passwords do not match");
		}
	}

	public function validate()
	{
		$this->_validateEmail();
		$this->_validateUsername();
		$this->_validatePassword();
		$this->_validatePwConfirm();
		if (!empty($this->_errors)) {
			require_once "core/NotValidException.class.php";
			throw new NotValidException($this->_errors);
		}
	}

	public function save()
	{
		throw new PDOException();
	}
}
