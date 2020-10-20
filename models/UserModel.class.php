<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   UserModel.class.php                                :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/20 19:41:23 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/20 19:41:23 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "BaseModel.class.php";

class UserModel extends BaseModel
{
	private $_errors = [];
	private $_email = "";
	private $_username = "";
	private $_password = "";
	private $_passwordConfirm = "";

	public function setAttributes($params)
	{
		$this->_email = $params["email"];
		$this->_username = $params["username"];
		$this->_password = $params["password"];
		$this->_passwordConfirm = $params["password_confirm"];
	}

	public function findOne($filter)
	{

	}

	private function _setError($attribute, $error)
	{
		$this->_errors[$attribute][] = $error;
	}

	private function _validateEmail()
	{

	}

	private function _validateUsername()
	{

	}

	private function _validatePassword()
	{

	}

	private function _validatePwConfirm()
	{

	}

	public function validate()
	{
		$this->_validateEmail();
		$this->_validateUsername();
		$this->_validatePassword();
		$this->_validatePwConfirm();
		if (!empty($this->_errors)) {
			throw new Exception();
		}
	}
}
