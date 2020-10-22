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
	protected $id = null;
	protected $email = "";
	protected $username = "";
	protected $password = "";
	private $passwordConfirm = "";
	protected $email_confirmed = false;

	public function getId()
	{
		return $this->id;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getEmailConfirmed()
	{
		return $this->email_confirmed;
	}

	protected function _tableName()
	{
		return "users";
	}

	protected function _propertiesInDb()
	{
		return ["email", "username", "password", "email_confirmed"];
	}

	public function login($params)
	{
		$this->setAttributes($params);
		$this->_validateUsername();
		$this->_validatePassword();
		if (!empty($this->errors)) {
			return null;
		}
		$user = self::findOne(["username" => $this->username]);
		if ($user && password_verify($params["password"], $user->password)) {
			return $user;
		} else {
			return null;
		}
	}

	public function setAttributes($params)
	{
		$this->email = $params["email"] ?? "";
		$this->username = $params["username"] ?? "";
		$this->password = $params["password"] ?? "";
		$this->passwordConfirm = $params["password_confirm"] ?? "";
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
		$this->password = password_hash($this->password, PASSWORD_BCRYPT);
		$this->passwordConfirm = "";
		if (!empty($this->_errors)) {
			require_once "core/NotValidException.class.php";
			throw new NotValidException($this->_errors);
		}
	}

	public function __toString()
	{
		$str = "UserModel(" . PHP_EOL;
		$str .= "id: " . $this->id . PHP_EOL;
		$str .= "email: " . $this->email . PHP_EOL;
		$str .= "username: " . $this->username . PHP_EOL;
		$str .= "password: " . $this->password . PHP_EOL;
		$str .= ")";
		return $str;
	}
}
