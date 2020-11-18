<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   resetpassword.php                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/18 13:52:23 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/18 13:52:23 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>


<div class="ui grid">
<form class="ui form six wide column centered" method="post">
  <div class="field required">
    <label>Enter your email address</label>
    <input
		type="text"
		name="email"
		placeholder="Email address"
		id="email"
		required
	>
  </div>
  <button class="ui button primary" type="submit" id="submit">Send</button>
  <button class="ui button" type="button" onclick="window.location.href='/'">Cancel</button>
</form>
</div>
