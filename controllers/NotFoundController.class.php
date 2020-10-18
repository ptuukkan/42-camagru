<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   NotFound.class.php                                 :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:12:57 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:12:57 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "core/BaseController.class.php";

class NotFoundController extends BaseController
{
	public function run()
	{
		http_response_code(404);
		$this->render("main", "notfound");
	}

	public function __toString()
	{
		$str = "NotFound(" . PHP_EOL;
		$str .= ")";
		return $str;
	}
}
