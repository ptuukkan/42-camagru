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

class BaseController
{
	public static $verbose = false;
	protected $_request;

	public function __construct($request)
	{
		$this->_request = $request;

		if (self::$verbose) {
			print("Controller instance constructed" . PHP_EOL);
		}
	}

	public function run()
	{
		call_user_func(array($this, $this->_request->action), $this->_request->params);
	}

	public function render($layout, $view = null)
	{
		ob_start();
		require_once "views/layouts/" . $layout . ".php";
		$layout = ob_get_clean();
		ob_start();
		require_once "views/" . $view . ".php";
		$view = ob_get_clean();
		echo str_replace("{{view}}", $view, $layout);
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print("Controller instance destructed" . PHP_EOL);
		}
	}

}
