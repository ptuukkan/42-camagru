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
	protected $id = null;
	protected $email = "";
	protected $username = "";
	protected $password = "";
	protected $active = false;
	protected $token = "";
	protected $notifications = true;
	private $_passwordConfirm = "";
	private $_newPassword = "";
	private $_passwordChanged = false;
	private $_errors = [];

	public function __construct($params = [])
	{
		if (!empty($params)) {
			$this->email = $params["email"] ?? "";
			$this->username = $params["username"] ?? "";
			$this->password = $params["password"] ?? "";
			$this->_passwordConfirm = $params["password_confirm"] ?? "";
			$this->_newPassword = $params["new_password"] ?? "";
			$this->notifications = ($params["notifications"]) ?? true;
		}
	}

	public function getId() { return $this->id; }

	public function getEmail() { return $this->email; }

	public function setEmail($email) { $this->email = $email; }

	public function getUsername() { return $this->username; }

	public function setUsername($username) { $this->username = $username ;}

	public function getToken() { return $this->token; }

	public function clearToken() { $this->token = ""; }

	public function getPassword() { return $this->password; }

	public function setPassword($password) { $this->password = $password; }

	public function setPwConfirm($password) { $this->_passwordConfirm = $password; }

	public function getPwConfirm() { return $this->_passwordConfirm; }

	public function getNewPassword() { return $this->_newPassword; }

	public function getNotifications() { return $this->notifications; }

	public function setNotifications($value) { $this->notifications = $value; }

	public function getErrors() { return $this->_errors; }

	public function hasErrors()
	{
		if (empty($this->_errors)) {
			return false;
		}
		return true;
	}

	public function setError($attribute, $error)
	{
		$this->_errors[$attribute][] = $error;
	}

	public function isActive() { return $this->active; }

	public function setActive() { $this->active = true; }

	protected function _tableName() { return "users"; }

	protected function _propertiesInDb()
	{
		if ($this->_passwordChanged) {
			return ["email", "username", "password", "active", "token", "notifications"];
		}
		return ["email", "username", "active", "token", "notifications"];
	}

	public function setPasswordChanged() { $this->_passwordChanged = true; }

	public function generateToken()
	{
		while (true) {
			$this->token = bin2hex(random_bytes(50));
			if (!UserModel::findOne(["token" => $this->token], ["id"])) {
				break;
			}
		}

	}

	public function verifyPassword($password)
	{
		if (password_verify($password, $this->password)) {
			return true;
		}
		return false;
	}

	public function save()
	{
		if ($this->_passwordChanged) {
			$this->password = password_hash($this->password, PASSWORD_BCRYPT);
		}
		parent::save();
	}

	public function validateEmail()
	{
		$valid = filter_var($this->email, FILTER_VALIDATE_EMAIL);
		if (!$valid) {
			$this->setError("email", "Email address is not valid");
		}
		if ($valid && self::findOne(["email" => $this->email])) {
			$this->setError("email", "Email address is already in use");
		}
	}

	public function validateUsername()
	{
		if (strlen($this->username) < 3) {
			$this->setError("username", "Username must be at least 3 characters");
		}
		$valid = !filter_var($this->username, FILTER_VALIDATE_REGEXP, [
			"options" => ["regexp" => "/[^a-zA-Z0-9]/"]
		]);
		if (!$valid) {
			$this->setError("username", "Username must contain only alphanumeric characters");
		}
		if ($valid && self::findOne(["username" => $this->username])) {
			$this->setError("username", "Username is already in use");
		}

	}

	public function validatePassword()
	{
		if (strlen($this->password) < 8) {
			$this->setError("password", "Password must be at least 8 characters");
		}
		if (!filter_var($this->password, FILTER_VALIDATE_REGEXP, [
			"options" => ["regexp" => "/[A-Z]/"]
		])) {
			$this->setError("password", "Password must contain at least 1 uppercase character");
		}
		if (!filter_var($this->password, FILTER_VALIDATE_REGEXP, [
			"options" => ["regexp" => "/[0-9]/"]
		])) {
			$this->setError("password", "Password must contain at least 1 number");
		}
	}

	public function validatePwConfirm()
	{
		if ($this->password !== $this->_passwordConfirm) {
			$this->setError("password_confirm", "Passwords do not match");
		}
	}

	public function validate()
	{
		try {
			$this->validateEmail();
			$this->validateUsername();
			$this->validatePassword();
			$this->validatePwConfirm();
		} catch (Exception $e) {
			$this->setError("global", "Internal server error");
		}
		if (!empty($this->_errors)) {
			throw new Exception();
		}
	}
}
