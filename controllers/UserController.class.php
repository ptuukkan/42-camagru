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
			return;
		}
		View::renderView("main", "login");
	}

	public function signup($params)
	{
		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}
		View::renderView("main", "signup");
	}

	public function logout($params)
	{
		if (Application::$app->session->loggedIn) {
			Application::$app->session->logout();
			header("Location: /");
		}
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
		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}
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
				$errors["global"][] = "Internal server error";
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
		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}
		$user = new UserModel($params);
		$user->setPasswordChanged();
		try {
			$user->validate();
			$user->generateToken();
			$user->save();
		} catch (Exception $e) {
			$errors = $user->getErrors();
			if ($e instanceof PDOException) {
				$errors["global"][] = "Internal server error";
			}
			View::renderView("main", "signup", [
				"values" => $params, "errors" => $errors
			]);
			return;
		}
		$this->_sendVerificationEmail($user);
		$message["status"] = "success";
 		$message["header"] = "Success";
		$message["body"] = "Before logging in, please verify your email by clicking the link we have sent to you.";
		View::renderMessage("main", $message);
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
				throw new Exception("Internal server error");
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
		$user;

		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}

		if (!isset($params["token"]) || strlen($params["token"]) !== 100 ||
			!ctype_xdigit($params["token"])) {
		 	throw new HttpException("Invalid token or user already verified", 400);
		}
		try {
			$user = UserModel::findOne(["token" => $params["token"]]);
			if (!$user) {
				throw new HttpException("Invalid token or user already verified", 400);
			}
			$user->setActive();
			$user->clearToken();
			$user->save();
		} catch (PDOException $e) {
			throw new HttpException("Internal server error", 500);
		}
		$message["status"] = "success";
		$message["header"] = "Success";
		$message["body"] = "Email address successfully verified, you can now log in.";
		View::renderMessage("main", $message);
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

	public function resetPassword($params)
	{
		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}
		View::renderView("main", "resetpassword");
	}

	public function sendResetPassword($params)
	{
		$message = [];

		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}
		$message["status"] = "success";
		$message["header"] = "Success";
		$message["body"] = "Password reset email has been sent to your email address";
		if (isset($params["email"]) && filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
			try {
				$user = UserModel::findOne(["email" => $params["email"]]);
				if ($user && $user->isActive()) {
					$user->generateToken();
					$user->save();
					$this->_sendResetPasswordEmail($user);
				}
			} catch (Exception $e) {
				$message["status"] = "error";
				$message["header"] = "Oops";
				$message["body"] = "Something went wrong, please try again";
			}

		}
		View::renderMessage("main", $message);
	}

	private function _sendResetPasswordEmail($user)
	{
		$token = $user->getToken();
		$email = $user->getEmail();
		$username = $user->getUsername();
		$subject = 'Reset your password';
		$message = "
		<html>
		<head>
			<title>Camagru password reset</title>
		</head>
		<body>
			<p>Please set up your new password by clicking the link below</p>
			<a href='localhost/newpassword?token=" . $token . "'>Reset Password</a>
		</body>
		</html>
		";
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = 'To: ' . $username . ' <' . $email . '>';
		$headers[] = 'From: Camagru <no-reply@camagru.com>';

		mail($email, $subject, $message, implode("\r\n", $headers));
	}

	public function newPasswordForm($params)
	{
		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}
		if (!isset($params["token"]) || strlen($params["token"]) !== 100 ||
			!ctype_xdigit($params["token"])) {
		 	throw new HttpException("Invalid token", 400);
		}
		View::renderView("main", "newpassword");
	}

	public function newPassword($params)
	{
		$user = new UserModel();

		if (Application::$app->session->loggedIn) {
			header("Location: /");
			return;
		}
		$token = "";
		if (isset($_GET["token"])) {
			$token = filter_var($_GET["token"], FILTER_SANITIZE_SPECIAL_CHARS);
		}
		if (strlen($token !== 100 || !ctype_xdigit($token))) {
			throw new HttpException("Invalid token", 400);
		}
		if (!isset($params["password"]) || !isset($params["password_confirm"])) {
			throw new HttpException("Bad request", 400);
		}
		try {
			$user = UserModel::findOne(["token" => $_GET["token"]]);
			if (!$user) {
				throw new HttpException("Invalid token", 400);
			}
			$user->setPassword($params["password"]);
			$user->setPwConfirm($params["password_confirm"]);
			$user->validatePassword();
			$user->validatePwConfirm();
			if (!$user->hasErrors()) {
				$user->setPasswordChanged();
				$user->clearToken();
				$user->save();
			}
		} catch (PDOException $e) {
			$user->setError("global", "Internal server error");
		}
		if ($user->hasErrors()) {
			View::renderView("main", "newpassword", [
				"values" => $params,
				"errors" => $user->getErrors(),
				"status" => "error"
			]);
		} else {
			View::renderMessage("main", [
				"status" => "success",
				"header" => "Success",
				"body" => "Password changed!"
			]);
		}

	}
}
