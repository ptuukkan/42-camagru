<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   thumbnail.php                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/08 20:53:16 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/08 20:53:16 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>

<div class="card thumbcard" id="<?= $image->getId() ?>">
	<div class="image">
		<img src="<?= $image->getImgPath() ?>">
	</div>
	<div class="extra content" style="text-align: center">
		<button class="ui button negative fluid compact" style="padding-left: 0px; padding-right: 0px"><i class="trash large icon" style="margin: 0px"></i></button>
	</div>
</div>
