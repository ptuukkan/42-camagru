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

	public function get($path, $callback)
	{
		$this->_routes["get"][$path] = $callback;
	}

	public function post($path, $callback)
	{
		$this->_routes["post"][$path] = $callback;
	}

	private function _getCallback()
	{
		if (isset($this->_routes[$this->_request->method][$this->_request->path])) {
			$callback = $this->_routes[$this->_request->method][$this->_request->path];
			require_once "controllers/" . $callback[0] . ".class.php";
			if (method_exists($callback[0], $callback[1])) {
				return $callback;
			}
		} else {
			return false;
		}
	}

	public function route()
	{
		$callback = $this->_getCallback();
		if ($callback) {
			require_once "controllers/" . $callback[0] . ".class.php";
			$controller = new $callback[0]($callback[1]);
		} else {
			throw new HttpException("Page not found", 404);
		}
		return $controller;
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
