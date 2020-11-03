<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   LikeModel.class.php                                :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/03 20:58:21 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/03 20:58:21 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class LikeModel extends BaseModel
{
	protected $user_id;
	protected $img_id;

	public function __construct($img_id)
	{
		$this->img_id = $img_id;
		$this->user_id = Application::$app->session->userId;
	}

	protected function _tableName()
	{
		return "likes";
	}

	protected function _propertiesInDb()
	{
		return ["user_id", "img_id"];
	}

	public function addLike($img_id)
	{
		
	}
}
