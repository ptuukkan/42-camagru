<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   profile.php                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/11 14:57:19 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/11 14:57:19 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>

<div class="ui grid">
<form class="ui form six wide column centered <?= (isset($params["status"])) ? $params["status"] : "" ?>" method="post">
	<div class="ui success message">
		<p>Saved</p>
	</div>
  <div class="field">
    <label>Email address</label>
    <input
      type="text"
      name="email"
      id="email"
      placeholder="Email address"
      value=<?= $params["values"]["email"] ?>
    >
	  <?= self::_printFieldErrors("email", $params) ?>
  </div>
  <div class="field">
    <label>Username</label>
    <input
      type="text"
      name="username"
      id="username"
      placeholder="Username"
      value=<?= $params["values"]["username"] ?>
    >
    <?= self::_printFieldErrors("username", $params) ?>
  </div>
  <div class="field required">
    <label>Current Password</label>
    <input
      type="password"
      name="password"
      id="password"
      placeholder="Password"
      value=<?= (isset($params["values"]["password"])) ? $params["values"]["password"] : "" ?>
    >
    <?= self::_printFieldErrors("password", $params) ?>
  </div>
  <div class="field">
    <label>New Password</label>
    <input
      type="password"
      name="new_password"
      id="new_password"
      placeholder="New Password"
      value=<?= (isset($params["values"]["new_password"])) ? $params["values"]["new_password"] : "" ?>
    >
    <?= self::_printFieldErrors("new_password", $params) ?>
  </div>
  <div class="field">
    <label>Confirm password</label>
    <input
      type="password"
      name="password_confirm"
      id="password_confirm"
      placeholder="Confirm Password"
      value=<?= (isset($params["values"]["password_confirm"])) ? $params["values"]["password_confirm"] : "" ?>
    >
    <?= self::_printFieldErrors("password_confirm", $params) ?>
  </div>
  <?= self::_printFieldErrors("global", $params) ?>
  <button class="ui button primary" type="submit">Save</button>
  <button class="ui button" type="button" onclick="window.location.href='/'">Cancel</button>
</form>
</div>
<script src=/public/js/profile.js></script>
