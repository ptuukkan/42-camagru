<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ImageModel.class.php                               :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/01 16:46:29 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/01 16:46:29 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "BaseModel.class.php";
require_once "UserModel.class.php";
require_once "CommentModel.class.php";

class ImageModel extends BaseModel
{
	protected $id;
	protected $user_id;
	protected $img_path;
	protected $img_date;
	private $_imgData;

	public function __construct($params = [])
	{
		if (!empty($params)) {
			$this->user_id = Application::$app->session->userId;
			$this->_imgData = $params["img_data"];
			$this->img_date = time();
		}
	}

	public function getDate()
	{
		return $this->date_added;
	}

	protected function _tableName()
	{
		return "images";
	}
	protected function _propertiesInDb()
	{
		return ["user_id", "img_path", "img_date"];
	}

	public static function getImages()
	{
		$fields = ["id", "img_path", "img_date", "user_id"];
		$images = self::findMany($fields);
		$size = count($images);
		for ($i = 0; $i < $size; $i++) {
			$images[$i]["user"] = UserModel::findOne(["id" => $images[$i]["user_id"]],
				["username"]);
			$comments = CommentModel::findMany(["comment_date", "comment", "user_id"],
				["img_id" => $images[$i]["id"]]);
			usort($comments, function($first, $second) {
				return $first["comment_date"] < $second["comment_date"];
			});
			$images[$i]["comments"] = $comments;
			$comments_size = count($images[$i]["comments"]);
			for ($n = 0; $n < $comments_size; $n++) {
				$images[$i]["comments"][$n]["user"] = UserModel::findOne(["id" =>
					$images[$i]["comments"][$n]["user_id"]], ["username"]);
			}
		}
		return $images;
	}

	public function validate()
	{
		$parts = explode(',', $this->_imgData);
		if (count($parts) !== 2) {
			return false;
		}
		if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $parts[1])) {
			return false;
		}
		$imgType = str_replace(
			['data:image/', ';', 'base64'],
			['', '', '',],
			$parts[0]
		);
		$allowedTypes = ['png', 'jpg', 'jpeg'];
		if (!in_array($imgType, $allowedTypes)) {
			return false;
		}
		$this->img_path = "/public/img/uploads/" . uniqid("img_") . "." . $imgType;
		$this->_imgData = $parts[1];
	}

	public function save()
	{
		parent::save();
		if (!file_put_contents($this->img_path, $this->_imgData)) {
			$this->delete();
			throw new HttpException("Cannot save image", 500, true);
		}
	}
}
