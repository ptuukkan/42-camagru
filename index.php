<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   index.php                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/05 18:47:06 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/05 18:47:06 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "core/Router.class.php";
require_once "core/Request.class.php";

$request = new Request();
$router = new Router($request);
$router->get("/", [GalleryController::class, "index"]);
$router->get("/login", [UserController::class, "login"]);
$router->get("/signup", [UserController::class, "signup"]);
$router->post("/login", [UserController::class, "handleLogin"]);
$router->post("/signup", [UserController::class, "handleSignup"]);
$controller = $router->route();
$controller->run();

