<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   CommentModel.class.php                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/01 20:34:22 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/01 20:34:22 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class CommentModel extends BaseModel
{
	protected $id;
	protected $comment_date;
	protected $comment;
	protected $img_id;
	protected $user_id;

	public function __construct($img_id, $comment)
	{
		$this->img_id = $img_id;
		$this->comment = $comment;
		$this->comment_date = time();
		$this->user_id = Application::$app->session->userId;
		parent::__construct();
	}

	public function save()
	{
		$this->id = $this->_insert();
		return true;
	}

	protected function _tableName()
	{
		return "comments";
	}

	protected function _propertiesInDb()
	{
		return ["comment_date", "comment" ,"img_id", "user_id"];
	}
}
