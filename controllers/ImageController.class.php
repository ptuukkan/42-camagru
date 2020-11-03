<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ImageController.class.php                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:35:54 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:35:54 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "BaseController.class.php";
require_once "models/ImageModel.class.php";

class ImageController extends BaseController
{
	public function index($params)
	{
		$images = ImageModel::getImages();
		usort($images, function($first, $second) {
			return $first["img_date"] < $second["img_date"];
		});
		//print_r($images);
		 View::renderView("main", "gallery", $images);
	}

	public function edit($params)
	{
		// if (!Application::$app->session->loggedIn) {
		// 		throw new Exception("Unauthorized", 401);
		// }
		View::renderView("main", "edit");
	}

	public function savePhoto($params)
	{
		$image = new ImageModel();
		$image->newImage($params);
	}

	public function addComment($params)
	{
		if (!Application::$app->session->loggedIn) {
			http_response_code(401);
			echo json_encode(["error" => "Not authorized"]);
			return;
		}
		if (isset($params["img_id"]) && isset($params["comment"])) {
			$comment = new CommentModel($params["img_id"], $params["comment"]);
			try {
				$comment->save();
				echo json_encode([
					"comment_id" => $comment->getId(),
					"comment_date" => CommentModel::timeToString($comment->getDate()),
					"comment" => $comment->getComment(),
					"img_id" => $comment->getImgId(),
					"user" => UserModel::findOne(["username"], ["id" => $comment->getUserId()])
				]);
			} catch (Exception $e) {
				http_response_code(500);
				echo json_encode(["error" => "Server error"]);
			}
		} else {
				http_response_code(400);
				echo json_encode(["error" => "Bad request"]);
		}
	}

	public function addLike($params)
	{
		if (!Application::$app->session->loggedIn) {
			http_response_code(401);
			echo json_encode(["error" => "Not authorized"]);
			return;
		}
		if (!isset($params["img_id"])) {
			http_response_code(400);
			echo json_encode(["error" => "Bad request"]);
			return;
		}
		try {
			$image = ImageModel::findOne(["id" => $params["img_id"]]);
			if (!$image) {
				http_response_code(400);
				echo json_encode(["error" => "Bad request"]);
				return;
			}
			$likes = ImageModel::addLike($image);
			echo json_encode(["likes" => $likes]);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["error" => "Server error"]);
		}
	}
}
