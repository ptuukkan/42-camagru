<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   gallery.php                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:37:05 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:37:05 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>
<div class="ui grid">
	<div class="ten wide column centered">
		<?php
			foreach ($params["images"] as $image) {
				echo self::_printImage($image);
			}
		?>
		<?= self::_printPagination($params) ?>
	</div>
</div>
<script src=/public/js/gallery.js></script>


