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
		View::renderView("main", "login");
	}

	public function signup($params)
	{
		View::renderView("main", "signup");
	}

	public function handleSignup($params)
	{
		try {
			$user = new UserModel();
			$user->setAttributes($params);
			$user->validate();
			$user->save();
		} catch (NotValidException $e) {
			View::renderView("main", "signup", [
				"values" => $params, "errors" => $e->getErrors()
			]);
		}
	}
}
