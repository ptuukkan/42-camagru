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
				return $first->getDate() < $second->getDate();
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
		$images;

		if (!Application::$app->session->loggedIn) {
				throw new HttpException("Unauthorized", 401);
		}
		try {
			$images = ImageModel::findMany([], [
				"user_id" => Application::$app->session->userId
			]);
			usort($images, function($first, $second) {
				return $first->getDate() < $second->getDate();
			});
		} catch (Exception $e) {
			throw new HttpException($e->getMessage(), 500);
		}

		View::renderView("main", "edit", $images);
	}

	public function addImage($params)
	{
		$image;

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Not authorized", 401, true);
		}
		if (!isset($params["img_data"])) {
			throw new HttpException("Bad request, img data not set", 400, true);
		}
		try {
			$image = new ImageModel($params);
			if (!$image->validate()) {
				throw new HttpException("Bad request, img is not valid", 400, true);
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

	public function deleteImage($params)
	{
		$image;
		$likes;

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Not authorized", 401, true);
		}
		if (!isset($params["img_id"])) {
			throw new HttpException("Bad request", 400, true);
		}
		try {
			$image = ImageModel::findOne(["id" => $params["img_id"]]);
			if (!$image->isOwner()) {
				throw new HttpException("Not authorized", 401, true);
			}
			foreach ($image->comments as $comment) {
				$comment->remove();
			}
			$likes = LikeModel::findMany([], ["img_id" => $image->getId()]);
			foreach ($likes as $like) {
				$like->remove();
			}
			$image->remove();
		} catch (Exception $e) {
			throw new HttpException($e->getMessage(), 500, true);
		}

		echo json_encode($params["img_id"]);
	}

	public function addComment($params)
	{
		$comment;

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Not authorized", 401, true);
		}
		if (!isset($params["img_id"]) || !isset($params["comment_text"]) ||
			strlen($params["comment_text"]) < 1) {
			throw new HttpException("Bad request", 400, true);
		}
		try {
			if (!ImageModel::findOne(["id" => $params["img_id"]])) {
				throw new HttpException("Bad request, img not found", 400, true);
			}
			$comment = new CommentModel($params);
			$comment->save();
		} catch (PDOException $e) {
			throw new HttpException($e->getMessage(), 500, true);
		}

		echo json_encode([
			"comment_text" => $comment->getCommentText(),
			"comment_date" => $comment->timeToString(),
			"comment_username" => $comment->user->getUsername()
		]);
	}

	public function handleLike($params)
	{
		$like;
		$imgLikes;
		$image;

		if (!Application::$app->session->loggedIn) {
			throw new HttpException("Not authorized", 401, true);
		}
		if (!isset($params["img_id"])) {
			throw new HttpException("Bad request", 400, true);
		}
		try {
			$image = ImageModel::findOne(["id" => $params["img_id"]]);
			if (!$image) {
				throw new HttpException("Bad request", 400, true);
			}
			$imgLikes = $image->getLikes();
			if ($image->isLiked()) {
				$like = LikeModel::findOne([
					"user_id" => Application::$app->session->userId,
					"img_id" => $params["img_id"]
				]);
				$like->remove();
				$imgLikes--;
			} else {
				$like = new LikeModel($params);
				$like->save();
				$imgLikes++;
			}
		} catch (PDOException $e) {
			throw new HttpException($e->getMessage(), 500, true);
		}

		echo json_encode($imgLikes);
	}
}
