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
	protected $comment_text;
	protected $img_id;
	protected $user_id;
	public $user;

	public function __construct($params = [])
	{
		if (!empty($params)) {
			$this->img_id = $params["img_id"];
			$this->comment_text = $params["comment_text"];
			$this->comment_date = time();
			$this->user_id = Application::$app->session->userId;
		}
		$this->user = UserModel::findOne(["id" => $this->user_id]);
	}

	public function getId() { return $this->id; }

	public function getDate() { return $this->comment_date; }

	public function getComment() { return $this->comment; }

	public function getUserId() { return $this->user_id; }

	public function getImgId() { return $this->img_id; }

	public function save()
	{
		$this->id = $this->insert();
		return true;
	}

	protected function _tableName()
	{
		return "comments";
	}

	protected function _propertiesInDb()
	{
		return ["comment_date", "comment_text" ,"img_id", "user_id"];
	}

	public function timeToString()
	{
		$currentTime = time();
		$seconds_ago = $currentTime - $this->comment_date;
		if ($seconds_ago >= 31536000) {
			return intval($seconds_ago / 31536000) . " years ago";
		} elseif ($seconds_ago >= 2419200) {
			return intval($seconds_ago / 2419200) . " months ago";
		} elseif ($seconds_ago >= 86400) {
			return intval($seconds_ago / 86400) . " days ago";
		} elseif ($seconds_ago >= 3600) {
			return intval($seconds_ago / 3600) . " hours ago";
		} elseif ($seconds_ago >= 60) {
			return intval($seconds_ago / 60) . " minutes ago";
		} else {
			return "less than a minute ago";
		}
	}
}
