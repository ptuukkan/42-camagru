<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   NotValidException.class.php                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/20 22:26:26 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/20 22:26:26 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class NotValidException extends Exception
{
	private $_errors;

	public function __construct($errors)
	{
		$this->_errors = $errors;
		parent::__construct();
	}

	public function getErrors()
	{
		return $this->_errors;
	}
}
