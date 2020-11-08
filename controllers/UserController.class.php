<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   User.class.php                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:35:54 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:35:54 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "BaseController.class.php";
require_once "models/UserModel.class.php";

class UserController extends BaseController
{
	public function login($params)
	{
		if (Application::$app->session->loggedIn) {
			header("Location: /");
		}
		View::renderView("main", "login");
	}

	public function signup($params)
	{
		if (Application::$app->session->loggedIn) {
			header("Location: /");
		}
		View::renderView("main", "signup");
	}

	public function logout($params)
	{
		Application::$app->session->logout();
		header("Location: /");
	}

	public function profile($params)
	{
		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Unauthorized", 401);
		}
		$user = new UserModel();
		$params = [];
		try {
			$user = UserModel::findOne(["id" => Application::$app->session->userId],
				["email", "username", "notifications"]);
			$params["values"] = $user;
		} catch (Exception $e) {
			$params["status"] = "error";
			$params["errors"]["global"][] = $e->getMessage();
		};
		View::renderView("main", "profile", $params);
	}

	public function handleLogin($params)
	{
		try {
			if (!isset($params["username"]) || !isset($params["password"])) {
				throw new Exception("Login failed");
			}
			$user = UserModel::findOne(["username" => $params["username"]]);
			if (!$user || !$user->verifyPassword($params["password"])) {
				throw new Exception("Login failed");
			}
			if (!$user->isActive()) {
				throw new Exception("Email address is not confirmed");
			}
		} catch (Exception $e) {
			if ($e instanceof PDOException) {
				$errors["global"][] = "Server error";
			} else {
				$errors["global"][] = $e->getMessage();
			}
			View::renderView("main", "login", [
				"values" => $params, "errors" => $errors
			]);
			return;
		}
		Application::$app->session->setSession($user->getId());
		header("Location: /");
	}

	public function handleSignup($params)
	{
		$user = new UserModel($params);
		$user->setPasswordChanged();
		try {
			$user->validate();
			$user->generateToken();
			$user->save();
		} catch (Exception $e) {
			$errors = $user->getErrors();
			if ($e instanceof PDOException) {
				$errors["global"][] = $e->getMessage();
			}
			View::renderView("main", "signup", [
				"values" => $params, "errors" => $errors
			]);
			return;
		}
		$this->_sendVerificationEmail($user);
		$message["header"] = "Success";
		$message["body"] = "Before logging in, please verify your email by clicking the link we have sent to you.";
		View::renderMessage("main", "success", $message);
	}

	public function saveProfile($params)
	{
		$newUser;
		$user = new UserModel();
		$errors = [];
		$status = "success";

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Unauthorized", 401);
		}
		if (!isset($params["notifications"])) {
			$params["notifications"] = false;
		} else {
			$params["notifications"] = true;
		}
		$newUser = new UserModel($params);
		try {
			$user = UserModel::findOne(["id" => Application::$app->session->userId]);
			if (!$user) {
				throw new Exception("Server error");
			}
			if (!$user->verifyPassword($newUser->getPassword())) {
				$user->setError("current_password", "Current password is invalid");
			}
			if ($newUser->getEmail() !== $user->getEmail()) {
				$user->setEmail($newUser->getEmail());
				$user->validateEmail();
			}
			if ($newUser->getUsername() !== $user->getUsername()) {
				$user->setUsername($newUser->getUsername());
				$user->validateUsername();
			}
			if ($newUser->getNewPassword()) {
				$user->setPassword($newUser->getNewPassword());
				$user->setPwConfirm($newUser->getPwConfirm());
				$user->validatePassword();
				$user->validatePwConfirm();
				$user->setPasswordChanged();
			}
			if (!$user->hasErrors()) {
				$user->save();
			}
		} catch (Exception $e) {
			$user->setError("global", $e->getMessage());
		}
		if ($user->hasErrors()) {
			$status = "error";
		} else {
			unset($params["password"]);
			unset($params["password_confirm"]);
			unset($params["new_password"]);
		}
		View::renderView("main", "profile", [
			"values" => $params,
			"errors" => $user->getErrors(),
			"status" => $status
		]);
	}

	public function verifyEmail($params)
	{
		$status;
		$user;

		if (!isset($params["token"]) || strlen($params["token"]) !== 100 ||
			!ctype_xdigit($params["token"])) {
		 	throw new HttpException("Bad request", 400);
		}
		try {
			$user = UserModel::findOne(["token" => $params["token"]]);
			if (!$user) {
				throw new HttpException("Bad request", 400);
			}
			$user->setActive();
			$user->save();
		} catch (Exception $e) {
			throw new HttpException("Server error", 500);
		}
		$message["header"] = "Success";
		$message["body"] = "Email address successfully verified, you can now log in.";
		View::renderMessage("main", "success", $message);
	}

	private function _sendVerificationEmail($user)
	{
		$token = $user->getToken();
		$email = $user->getEmail();
		$username = $user->getUsername();
		$subject = 'Verify your email';
		$message = "
		<html>
		<head>
			<title>Camagru email verification</title>
		</head>
		<body>
			<p>Please verify your email by clicking the link below</p>
			<a href='localhost/verify?token=" . $token . "'>Verify Email</a>
		</body>
		</html>
		";
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = 'To: ' . $username . ' <' . $email . '>';
		$headers[] = 'From: Camagru <no-reply@camagru.com>';

		mail($email, $subject, $message, implode("\r\n", $headers));

	}
}
