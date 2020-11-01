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
		$images = ImageModel::findAll();
		usort($images, function($first, $second) {
			return $first->getDate() < $second->getDate();
		});
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
}
