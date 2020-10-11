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

class User extends BaseController
{
	public function login($params)
	{
		if ($this->_request->method === "post") {
			echo "posted!";
		}
		$this->render("main", "login");
	}

	public function signup($params)
	{
		if ($this->_request->method === "post") {
			echo "posted!";
		}
		$this->render("main", "signup");
	}

	public function __toString()
	{
		$str = "GalleryController(" . PHP_EOL;
		$str .= ")";
		return $str;
	}
}
