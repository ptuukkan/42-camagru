<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   image.php                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/01 18:01:30 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/01 18:01:30 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>

<div class="ui fluid card ten wide column centered" id="<?= $image->getId() ?>">
	<div class="content">
		<div class="right floated meta"><?= date("Y-m-d H:i:s", $image->getDate()) ?></div>
			<img class="ui avatar image" src="/public/img/user.png"> <?= $image->user->getUsername() ?>
	</div>
	<div class="image">
		<img src="<?= $image->getImgPath() ?>">
	</div>
	<div class="content">
		<span class="right floated">
			<i class="heart outline like icon like-button"></i>
			<span class="num-of-likes"><?= $image->getLikes() ?></span> likes
		</span>
		<i class="comment icon"></i>
		<span class="num-of-comments"><?= count($image->comments) ?></span> comments
	</div>
	<div class="extra content">
		<div class="ui large transparent left icon input" style="width: 100%">
			<i class="comment outline icon"></i>
			<input type="text" placeholder="Add Comment..." class="comment-input">
		</div>
	</div>
	<div class="ui comments">
		<?= self::_printComments($image->comments) ?>
	</div>
	<div class="extra content">
		<a class="show-comments " <?= (count($image->comments) > 1) ? '' : 'style="display: none"' ?>>
			View all comments
		</a>
	</div>
</div>
