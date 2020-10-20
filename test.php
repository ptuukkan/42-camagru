<?php

require_once "models/UserModel.class.php";

UserModel::$verbose = true;
$user = new UserModel();
