<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   BaseModel.class.php                                :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/20 19:41:23 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/20 19:41:23 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

abstract class BaseModel
{
	public static $verbose = false;

	public function __construct()
	{
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
