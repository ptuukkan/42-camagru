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
		foreach ($params as $image) {
		 	echo self::_printImage($image);
		}
	?>
	</div>
	<div class="ten wide column centered">
		<div class="ui pagination menu right floated">
			<a href="/?page=1">
				<div class="item active">1</div>
			</a>
			<a href="/?page=2">
				<div class="item">2</div>
			</a>
			<a href="/?page=3">
				<div class="item">3</div>
			</a>
		</div>
	</div>
</div>
<script src=/public/js/gallery.js></script>


