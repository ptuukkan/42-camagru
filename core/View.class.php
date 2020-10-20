<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   View.class.php                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/18 21:37:47 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/18 21:37:47 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class View
{
	public static $verbose = false;

	public function __construct($layout, $view)
	{
		if (self::$verbose) {
			print("View instance constructed" . PHP_EOL);
		}
	}

	public static function renderView($layout, $view)
	{
		ob_start();
		require_once "views/layouts/" . $layout . ".php";
		$layout = ob_get_clean();
		ob_start();
		require_once "views/" . $view . ".php";
		$view = ob_get_clean();
		echo str_replace("{{view}}", $view, $layout);
	}

	public static function renderMessage($layout, $message)
	{
		ob_start();
		require_once "views/layouts/" . $layout . ".php";
		$layout = ob_get_clean();
		$message = "<h2>$message</h2>";
		echo str_replace("{{view}}", $message, $layout);
	}

	public function __destruct()
	{
		if (self::verbose) {
			print("View instance destructed" . PHP_EOL);
		}
	}
}
