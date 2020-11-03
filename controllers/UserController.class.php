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
			throw new Exception("Unauthorized", 401);
		}
		$user = new UserModel();
		$params = [];
		try {
			$user = UserModel::findOne(["id" => Application::$app->session->userId],
				["email", "username"]);
			$params["values"] = $user;
		} catch (Exception $e) {
			$params["status"] = "error";
			$params["errors"]["global"][] = $e->getMessage();
		};
		View::renderView("main", "profile", $params);
	}

	public function handleLogin($params)
	{
		$user = new UserModel();
		try {
			$user = $user->login($params);
		} catch (Exception $e) {
			View::renderView("main", "login", [
				"values" => $params, "errors" => $user->getErrors()
			]);	$params["status"] = "success";
			return;
		}
		Application::$app->session->setSession($user->getId());
		header("Location: /");
	}

	public function handleSignup($params)
	{
		$user = new UserModel();
		try {
			$user->signUp($params);
		} catch (Exception $e) {
			View::renderView("main", "signup", [
				"values" => $params, "errors" => $user->getErrors()
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
		if (!Application::$app->session->loggedIn) {
			throw new Exception("Unauthorized", 401);
		}
		$user = new UserModel();
		try {
			$user->saveProfile($params);
		} catch (Exception $e) {
			View::renderView("main", "profile", [
				"values" => $params, 
				"errors" => $user->getErrors(),
				"status" => "error"
			]);
			return;
		}
		unset($params["password"]);
		unset($params["password_confirm"]);
		unset($params["new_password"]);
		View::renderView("main", "profile", [
			"values" => $params,
			"errors" => $user->getErrors(),
			"status" => "success"
		]);
	}

	public function verifyEmail($params)
	{
		if (!isset($params["token"]) || strlen($params["token"]) !== 100 ||
			!ctype_xdigit($params["token"])) {
		 	throw new Exception("Bad request", 400);
		}
		UserModel::verifyEmail($params["token"]);
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
