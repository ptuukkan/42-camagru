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

require_once "core/BaseController.class.php";

class UserController extends BaseController
{
	public function login($params)
	{
		$this->render("main", "login");
	}

	public function signup($params)
	{
		$this->render("main", "signup");
	}

	public function __toString()
	{
		$str = "GalleryController(" . PHP_EOL;
		$str .= ")";
		return $str;
	}
}
