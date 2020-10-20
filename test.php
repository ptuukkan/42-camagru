<?php

require_once "models/UserModel.class.php";
require_once "core/Application.class.php";

UserModel::$verbose = true;

$app = new Application();
$user = UserModel::findOne(["email" => "japa", "username" => "user"]);


$username = "japa838pepeAA";
var_dump(filter_var($username, FILTER_VALIDATE_REGEXP, [
	"options" => ["regexp" => "/^[a-zA-Z0-9]+$/"]
]));


