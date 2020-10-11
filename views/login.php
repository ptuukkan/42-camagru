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
<form class="ui form six wide column centered" method="post">
  <div class="field required">
    <label>Username</label>
    <input type="text" name="username" placeholder="Username">
  </div>
  <div class="field required">
    <label>Password</label>
    <input type="password" name="password" placeholder="Password">
  </div>
  <button class="ui button primary" type="submit">Login</button>
  <button class="ui button" type="button" onclick="window.location.href='/'">Cancel</button>
</form>
</div>
