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

class ImageModel extends BaseModel
{
	protected $id;
	protected $user_id;
	protected $img_type;
	protected $img_name;
	protected $likes = 0;
	protected $date_added;

	public function getDate()
	{
		return $this->date_added;
	}

	public function getFilename()
	{
		return '/public/img/uploads/' . $this->img_name . '.' . $this->img_type;
	}

	protected function _tableName()
	{
		return "images";
	}
	protected function _propertiesInDb()
	{
		return ["user_id", "img_type", "img_name", "likes", "date_added"];
	}

	private function _validateBase64($params)
	{
		$allowedExtensions = ['png', 'jpg', 'jpeg'];
		if (!isset($params["data"])) {
			return false;
		}
		$explode = explode(',', $params["data"]);
		if(count($explode) !== 2){
			return false;
		}
		if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
			return false;
		}
		$format = str_replace(
			['data:image/', ';', 'base64'],
			['', '', '',],
			$explode[0]
		);
		if (!in_array($format, $allowedExtensions)) {
			return false;
		}
		return ["type" => $format, "data" => base64_decode($explode[1])];
	}

	private function _saveImage($imgData)
	{
		if (!file_put_contents('public/img/uploads/' . $this->img_name . '.' . $this->img_type, $imgData["data"])) {
			throw new Exception("Cannot save image", 500);
		}
	}

	public function newImage($params)
	{
		$imgData = $this->_validateBase64($params);
		if (!$imgData) {
			http_response_code(400);
			return;
		}
		$this->img_name = uniqid("img_");
		$this->img_type = $imgData["type"];
		$this->user_id = Application::$app->session->userId;
		$this->date_added = time();
		try {
			$this->id = $this->_insert();
			$this->_saveImage($imgData);
		} catch (Exception $e) {
			if (!$e instanceof PDOException) {
				$this->_delete($this->id);
			}
			http_response_code(500);
			echo json_encode(["error" => $e->getMessage()]);
		}
	}
}
