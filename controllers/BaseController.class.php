<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   BaseController.class.php                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:13:12 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:13:12 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

abstract class BaseController
{
	public static $verbose = false;
	public $action;

	public function __construct($action)
	{
		$this->action = $action;

		if (self::$verbose) {
			print(static::class . " instance constructed" . PHP_EOL);
		}
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print(static::class . " instance destructed" . PHP_EOL);
		}
	}
}
