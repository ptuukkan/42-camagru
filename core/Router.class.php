<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Router.class.php                                   :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 21:38:07 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 21:38:07 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class Router
{
	public static $verbose = false;
	private $_request;
	private $_routes;

	public function __construct($request)
	{
		$this->_request = $request;

		if (self::$verbose) {
			print("Router instance constructed" . PHP_EOL);
		}
	}

	private function _getController()
	{
		if (isset($this->_routes[$this->_request->method][$this->_request->path])) {
			return $this->_routes[$this->_request->method][$this->_request->path];
		} else {
			return false;
		}
	}

	public function get($path, $controller)
	{
		$this->_routes["get"][$path] = $controller;
	}

	public function post($path, $controller)
	{
		$this->_routes["post"][$path] = $controller;
	}

	public function route()
	{
		$controller = $this->_getController();
		if ($controller) {
			require_once "controllers/" . $controller . ".class.php";
			$controller = new $controller($this->_request);
			if (method_exists($controller, $this->_request->action)) {
				return $controller;
			} else {
				require_once "controllers/NotFound.class.php";
				return new NotFound($this->_request);
			}
		} else {
			require_once "controllers/NotFound.class.php";
			return new NotFound($this->_request);
		}
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print("Router instance destructed" . PHP_EOL);
		}
	}

	private function _routesToString()
	{
		$str = "get:" . PHP_EOL;
		foreach ($this->_routes["get"] as $route => $controller) {
			$str .= $route . " => " . $controller . PHP_EOL;
		}
		$str .= "post:" . PHP_EOL;
		foreach ($this->_routes["post"] as $route => $controller) {
			$str .= $route . " => " . $controller . PHP_EOL;
		}
		return $str;
	}

	public function __toString()
	{
		$str = "Router(" . PHP_EOL;
		$str .= "Routes: " . $this->_routesToString() . PHP_EOL;
		$str .= ")";
		return $str;
	}
}
