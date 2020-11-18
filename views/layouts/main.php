<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   main.php                                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:26:50 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:26:50 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/public/styles/semantic.min.css">
	<link rel="stylesheet" href="/public/styles/style.css">
	<link rel="icon" type="image/png" href="/public/img/favicon.ico"/>
	<title>Camagru</title>
</head>
<body>
	<div class="ui large menu">
		<a class="item" href="/">Gallery</a>
		<a class="item" href="/edit">Edit</a>
		<div class="right menu">
			<?= (View::_loggedIn()) ?
			'<a class="item" href="/profile">Edit profile</a>
			<a class="item" href="/logout">Log out</a>' :
			'<a class="item" href="/login">Log in</a>
			<a class="item" href="/signup">Sign Up</a>'
			?>
		</div>
	</div>
	<div class="ui container">
		{{view}}
	</div>
	<div class="ui inverted vertical footer segment">
		<div class="ui container footer-container">
			&copy; ptuukkan 2020
		</div>
	</div>
</body>
</html>
