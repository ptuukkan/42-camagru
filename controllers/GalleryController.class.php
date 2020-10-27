<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Gallery.class.php                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:35:54 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:35:54 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "BaseController.class.php";

class GalleryController extends BaseController
{
	public function index($params)
	{
		View::renderView("main", "gallery");
	}

	public function edit($params)
	{
		if (!Application::$app->session->loggedIn) {
				throw new Exception("Unauthorized", 401);
			}
		View::renderMessage("main", "Edit Page");
	}
}
