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


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="module" src="./components/paragraph-group.js"></script>
	<script type="module" src="./components/my-paragraph.js"></script>
	<script type="module" src="./components/header-menu.js"></script>
	<script type="module" src="./components/top-header.js"></script>
	<script type="module" src="./components/user-panel.js"></script>
	<script type="module" src="./components/login-modal.js"></script>
	<title>Document</title>
</head>
<body>
	<top-header>
		<header-menu slot="header-menu"></header-menu>
		<user-panel slot="user-panel"></user-panel>
	</top-header>


</body>
</html>
