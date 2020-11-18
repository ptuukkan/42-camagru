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
	private $_request;
	private $_routes;

	public function __construct($request)
	{
		$this->_request = $request;
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
}
