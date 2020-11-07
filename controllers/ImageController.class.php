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
require_once "models/LikeModel.class.php";

class ImageController extends BaseController
{
	public function index($params)
	{
		$images;

		try {
			$images = ImageModel::findMany();
			usort($images, function($first, $second) {
				return $first->getImgDate() < $second->getImgDate();
			});
		} catch (Exception $e) {
			$message["header"] = "Error";
			$message["body"] = $e->getMessage();
			View::renderMessage("main", "error", $message);
		}

		View::renderView("main", "gallery", $images);
	}

	public function edit($params)
	{
		// if (!Application::$app->session->loggedIn) {
		// 		throw new Exception("Unauthorized", 401);
		// }
		View::renderView("main", "edit");
	}

	public function addImage($params)
	{
		$image;

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Not authorized", 401, true);
		}
		if (!isset($params["img_data"])) {
			throw new HttpException("Bad request", 400, true);
		}
		try {
			$image = new ImageModel($params);
			if (!$image->validate()) {
				throw new HttpException("Bad request", 400, true);
			}
			$image->save();
		} catch (PDOException $e) {
			throw new HttpException($e->getMessage(), 500, true);
		}

		echo json_encode([
			"img_id" => $image->getId(),
			"img_path" => $image->getImgPath()
		]);
	}

	public function addComment($params)
	{
		$comment;
		$user;

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Not authorized", 401, true);
		}
		if (!isset($params["img_id"]) || !isset($params["comment"]) ||
			strlen($params["comment"]) < 1) {
			throw new HttpException("Bad request", 400, true);
		}
		try {
			if (!ImageModel::findOne(["id" => $params["img_id"]])) {
				throw new HttpException("Bad request", 400, true);
			}
			$comment = new CommentModel($params);
			$comment->save();
			$user = UserModel::getCurrentUser();
		} catch (PDOException $e) {
			throw new HttpException($e->getMessage(), 500, true);
		}

		echo json_encode([
			"comment_text" => $comment->getCommentText(),
			"comment_date" => $comment->getCommentDate(),
			"comment_username" => $user->getUsername()
		]);
	}

	public function addLike($params)
	{
		$like;
		$imgLikes;

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Not authorized", 401, true);
		}
		if (!isset($params["img_id"])) {
			throw new HttpException("Bad request", 400, true);
		}
		try {
			if (!ImageModel::findOne(["id" => $params["img_id"]])) {
				throw new HttpException("Bad request", 400, true);
			}
			if (LikeModel::findOne([
				"user_id" => Application::$app->session->userId,
				"img_id" => $params["img_id"]
			])) {
				throw new HttpException("Bad request", 400, true);
			}
			$like = new LikeModel($params);
			$like->save();
			$imgLikes = LikeModel::findMany(["img_id" => $params["img_id"]]);
		} catch (PDOException $e) {
			throw new HttpException($e->getMessage(), 500, true);
		}

		echo json_encode(["likes" => count($imgLikes)]);
	}
}
