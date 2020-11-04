<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   HttpException.class.php                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/04 21:44:06 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/04 21:44:06 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class HttpException extends Exception
{
	public $json;

	public function __construct($message = null, $code = 0, $json = false)
	{
		$this->message = $message;
		$this->code = $code;
		$this->json = $json;

		http_response_code($this->code);
	}

	public function getJsonError()
	{
		return json_encode(["error" => $this->message]);
	}
}
