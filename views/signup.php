<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   register.php                                       :+:      :+:    :+:   */
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
    <label>Email address</label>
    <input type="text" name="email" id="email" placeholder="Email address">
	<?= self::_printFieldErrors("email", $params) ?>
  </div>
  <div class="field required">
    <label>Username</label>
    <input type="text" name="username" id="username" placeholder="Username">
  </div>
  <div class="field required">
    <label>Password</label>
    <input type="password" name="password" id="password" placeholder="Password">
  </div>
  <div class="field required">
    <label>Confirm password</label>
    <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirm Password">
  </div>
  <div class="ui success message">
    <div class="header">Registered!</div>
    <p>You have been succesfully signed up for Camagru. We've sent you an confirmation email!</p>
  </div>
  <button class="ui button primary" type="submit">Sign Up</button>
  <button class="ui button" type="button" onclick="window.location.href='/'">Cancel</button>
</form>
</div>
<!-- <script src=/public/js/signup.js></script> -->
