<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   login.php                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/11 14:57:19 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/11 14:57:19 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>

<div class="ui grid">
<form class="ui form six wide column centered <?= (isset($params["errors"])) ? "error" : "" ?>" method="post">
  <div class="field required">
    <label>Username</label>
    <input
		type="text"
		name="username"
		placeholder="Username"
		id="username"
		value=<?= (isset($params["values"]["username"])) ? $params["values"]["username"] : "" ?>
	>
  </div>
  <div class="field required">
    <label>Password</label>
    <input
		type="password"
		name="password"
		placeholder="Password"
		id="password"
		value=<?= (isset($params["values"]["password"])) ? $params["values"]["password"] : "" ?>
	>
  </div>
  <?= self::_printFieldErrors("global", $params) ?>
  <button class="ui button primary" type="submit" id="submit">Login</button>
  <button class="ui button" type="button" onclick="window.location.href='/'">Cancel</button>
</form>
</div>
