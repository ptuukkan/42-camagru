<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Request.class.php                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/09 17:57:10 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/09 17:57:10 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class Request
{
	public static $verbose = false;
	public $method;
	public $path;
	public $action = false;
	public $params = [];

	public function __construct()
	{
		$request_uri = strtolower(filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL));
		$array = explode("?", $request_uri);
		if (isset($array[1])) {
			$this->params = $array[1];
		}
		$array = array_values(array_filter(explode('/', $array[0])));
		$this->method = strtolower($_SERVER["REQUEST_METHOD"]);
		$this->path = $array[0] ?? "/";
		$this->action = $array[1] ?? "index";
		if (self::$verbose) {
			print("Request instance constructed" . PHP_EOL);
		}
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print("Request instance destructed" . PHP_EOL);
		}
	}

	public function __toString()
	{
		$str = "Request(" . PHP_EOL;
		$str .= "method: " . $this->method . PHP_EOL;
		$str .= "path: " . $this->path . PHP_EOL;
		$str .= "action: " . $this->action . PHP_EOL;
		$str .= "params: " . implode(";", (array)$this->params) . PHP_EOL;
		$str .= ")";
		return $str;
	}
}
