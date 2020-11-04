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
	private $_errors = [];
	protected $id = null;
	protected $email = "";
	protected $username = "";
	protected $password = "";
	private $_passwordConfirm = "";
	protected $active = true;
	protected $token = "";

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

	public function isActive()
	{
		return $this->active;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function getErrors()
	{
		return $this->_errors;
	}

	protected function _tableName()
	{
		return "users";
	}

	protected function _propertiesInDb()
	{
		return ["email", "username", "password", "active", "token"];
	}

	public static function getCurrentUser()
	{
		if (!Application::$app->session->loggedIn) {
			return null;
		}
		return self::findOne(["id" => Application::$app->session->userId]);
	}

	public function login($params)
	{
		$this->setAttributes($params);
		$this->_validateUsername(false);
		$this->_validatePassword();
		if (empty($this->_errors)) {
			$user = self::findOne(["username" => $this->username]);
			if ($user && password_verify($this->password, $user->password)) {
				if ($user->active) {
					return $user;
				} else {
					$this->_setError("global", "User email is not confirmed");
					throw new Exception();
				}
			}
		}
		unset($this->_errors);
		$this->_setError("global", "Login failed");
		throw new Exception();
	}

	public function signUp($params)
	{
		$this->setAttributes($params);
		$this->validate();
		if (!empty($this->_errors)) {
			throw new Exception();
		}
		$this->password = password_hash($this->password, PASSWORD_BCRYPT);
		$this->_passwordConfirm = "";
		$this->token = bin2hex(random_bytes(50));
		try {
			$this->insert();
		} catch (Exception $e) {
			$this->_setError("global", $e->getMessage());
			throw new Exception();
		}
	}

	public function saveProfile($params)
	{
		$this->setAttributes($params);
		$user = UserModel::findOne(["id" => Application::$app->session->userId]);
		if (!password_verify($params["password"], $user->password)) {
			$this->_setError("password", "Current password is invalid");
			throw new Exception();
		}
		if ($user->username !== $this->username) {
			$this->_validateUsername();
		}
		if ($user->email !== $this->email) {
			$this->_validateEmail();
		}
		if (!empty($this->_errors)) {
			throw new Exception();
		}
		$properties = ["email", "username"];
		if (strlen($params["new_password"]) > 0) {
			$this->password = $params["new_password"];
			$this->_validatePassword();
			$this->_validatePwConfirm();
			if (!empty($this->_errors)) {
				if (isset($this->_errors["password"])) {
					$this->_errors["new_password"] = $this->_errors["password"];
					unset($this->_errors["password"]);
				}
				throw new Exception();
			}
			$this->password = password_hash($this->password, PASSWORD_BCRYPT);
			$properties[] = "password";
		}
		try {
			$this->_update(Application::$app->session->userId, $properties);
		} catch (Exception $e) {
			$this->_setError("global", $e->getMessage());
		}
	}

	public static function verifyEmail($token)
	{
		try {
			$user = static::findOne(["active", "id"], ["token" => $token]);
		} catch (Exception $e) {
			throw new Exception("Internal server error", 500);
		}
		if (!$user) {
			throw new Exception("Bad request", 400);
		}
		if ($user->active) {
			return;
		}
		$user->active = true;
		try {
			$user->_update($user->id, ["active"]);
		} catch (Exception $e) {
			throw new Exception("Internal server error", 500);
		}
	}

	public function setAttributes($params)
	{
		$this->email = $params["email"] ?? "";
		$this->username = $params["username"] ?? "";
		$this->password = $params["password"] ?? "";
		$this->_passwordConfirm = $params["password_confirm"] ?? "";
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

	private function _validateUsername($unique = true)
	{
		if (strlen($this->username) < 3) {
			$this->_setError("username", "Username must be at least 3 characters");
		}
		$valid = !filter_var($this->username, FILTER_VALIDATE_REGEXP, [
			"options" => ["regexp" => "/[^a-zA-Z0-9]/"]
		]);
		if (!$valid) {
			$this->_setError("username", "Username must contain only alphanumeric characters");
		}
		if ($unique && $valid && self::findOne(["username" => $this->username])) {
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
		if ($this->password !== $this->_passwordConfirm) {
			$this->_setError("password_confirm", "Passwords do not match");
		}
	}

	public function validate()
	{
		$this->_validateEmail();
		$this->_validateUsername();
		$this->_validatePassword();
		$this->_validatePwConfirm();
	}

	public function __toString()
	{
		$str = "UserModel(" . PHP_EOL;
		$str .= "id: " . $this->id . PHP_EOL;
		$str .= "active: " . $this->active . PHP_EOL;
		$str .= "email: " . $this->email . PHP_EOL;
		$str .= "username: " . $this->username . PHP_EOL;
		$str .= "password: " . $this->password . PHP_EOL;
		$str .= "token: " . $this->token . PHP_EOL;
		$str .= ")";
		return $str;
	}
}
