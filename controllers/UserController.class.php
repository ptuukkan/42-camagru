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
			$user = UserModel::findOne(["id" => Application::$app->session->userId]);
		} catch (Exception $e) {
			$params["errors"]["global"][] = $e->getMessage();
		}
		$params["values"]["email"] = $user->getEmail();
		$params["values"]["username"] = $user->getUsername();
		$params["values"]["email_confirmed"] = $user->getEmailConfirmed();
		View::renderView("main", "profile", $params);
	}

	public function handleLogin($params)
	{
		$user = null;
		try {
			$user = new UserModel();
			$user = $user->login($params);
		} catch (Exception $e) {
			View::renderView("main", "login", [
				"values" => $params, "errors" => ["global" => [$e->getMessage()]]
			]);
		}
		if ($user) {
			Application::$app->session->setSession($user->getId(), $user->getEmailConfirmed());
			header("Location: /");
		} else {
			View::renderView("main", "login", [
				"values" => $params, "errors" => ["global" => ["Login failed"]]
			]);
		}
	}

	public function handleSignup($params)
	{
		$user = new UserModel();
		$user->setAttributes($params);
		try {
			$user->save();
		} catch (Exception $e) {
			View::renderView("main", "signup", [
				"values" => $params, "errors" => $-user>getErrors()
			]);
		}
		try {
			$id = $user->save();
		} catch (Exception $e) {
			View::renderView("main", "signup", [
				"values" => $params, "errors" => ["global" => [$e->getMessage()]]
			]);
		}
		Application::$app->session->setSession($id, $user->getEmailConfirmed());
		header("Location: /");
	}

	public function saveProfile($params)
	{
		try {
			$user = UserModel::findOne(["id" => Application::$app->session->userId]);

		} catch (Exception $e) {

		}
	}
}
