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
	private $_layout;
	private $_view;

	public function __construct($layout, $view)
	{
		$this->_layout = $layout;
		$this->_view = $view;

		if (self::$verbose) {
			print("View instance constructed" . PHP_EOL);
		}
	}

	public function renderView()
	{
		ob_start();
		require_once "views/layouts/" . $this->_layout . ".php";
		$layout = ob_get_clean();
		ob_start();
		require_once "views/" . $this->_view . ".php";
		$view = ob_get_clean();
		echo str_replace("{{view}}", $view, $layout);
	}

	public function renderMessage()
	{
		ob_start();
		require_once "views/layouts/" . $this->_layout . ".php";
		$layout = ob_get_clean();
		echo str_replace("{{view}}", $this->_view, $layout);
	}

	public function __destruct()
	{
		if (self::verbose) {
			print("View instance destructed" . PHP_EOL);
		}
	}
}
