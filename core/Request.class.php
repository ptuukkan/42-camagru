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
	public $method;
	public $path;
	public $params = [];

	public function __construct()
	{
		$this->method = strtolower($_SERVER["REQUEST_METHOD"]);
		$request_uri = strtolower(filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL));
		$array = explode("?", $request_uri);
		if ($this->method === "get") {
			$this->params = $this->_sanitizeParams($_GET);
		} else if ($this->method === "post") {
			$this->params = $this->_sanitizeParams($_POST);
		}
		$this->path = $array[0] ?? "/";
	}

	private function _sanitizeParams($params)
	{
		$newParams = [];
		foreach ($params as $key => $value) {
			$key = filter_var($key, FILTER_SANITIZE_SPECIAL_CHARS);
			$value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
			$newParams[$key] = $value;
		}
		return $newParams;
	}
}
