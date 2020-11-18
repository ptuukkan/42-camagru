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

abstract class BaseController
{
	public $action;

	public function __construct($action)
	{
		$this->action = $action;
	}
}
