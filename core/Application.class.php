<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   Application.class.php                              :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/18 21:09:57 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/18 21:09:57 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

require_once "core/Request.class.php";
require_once "core/Router.class.php";
require_once "core/View.class.php";
require_once "core/Database.class.php";
require_once "core/Session.class.php";

class Application
{
	public static $verbose = false;
	private $_request;
	private $_router;
	private $_controller;
	public $db;
	public $session;
	public static $app;

	public function __construct()
	{
		$this->_request = new Request();
		$this->_router = new Router($this->_request);

		$this->_router->get("/", [GalleryController::class, "index"]);
		$this->_router->get("/edit", [GalleryController::class, "edit"]);
		$this->_router->get("/login", [UserController::class, "login"]);
		$this->_router->get("/logout", [UserController::class, "logout"]);
		$this->_router->get("/signup", [UserController::class, "signup"]);
		$this->_router->get("/profile", [UserController::class, "profile"]);
		$this->_router->post("/login", [UserController::class, "handleLogin"]);
		$this->_router->post("/signup", [UserController::class, "handleSignup"]);
		$this->_router->post("/profile", [UserController::class, "saveProfile"]);

		$this->session = new Session();
		self::$app = $this;

		if (self::$verbose) {
			print("Application instance constructed" . PHP_EOL);
		}
	}

	public function run()
	{
		try {
			$this->db = new Database();
			$this->_controller = $this->_router->route();
			call_user_func([$this->_controller, $this->_controller->action], $this->_request->params);
		} catch (Exception $e) {
			View::renderMessage("main", $e->getCode() . "-" . $e->getMessage());
		}
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print("Application instance destructed" . PHP_EOL);
		}
	}
}
