<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   comments.php                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/01 22:04:17 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/01 22:04:17 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>
<div class="ui comments">
<div class="comment">
	<a class="avatar">
		<img src="/public/img/user.png">
	</a>
	<div class="content">
		<a class="author"><?= $comments[0]->user->getUsername() ?></a>
		<div class="metadata">
			<span class="date"><?= $comments[0]->timeToString() ?></span>
		</div>
		<div class="text">
			<?= $comments[0]->getCommentText() ?>
		</div>
	</div>
</div>
<?php
	if (count($comments) > 1) {
		$html = '<div class="collapsed comments">' . PHP_EOL;
		foreach (array_slice($comments, 1) as $comment) {
			$html .= '	<div class="comment">' . PHP_EOL;
			$html .= '		<a class="avatar">' . PHP_EOL;
			$html .= '			<img src="/public/img/user.png">' . PHP_EOL;
			$html .= '		</a>' . PHP_EOL;
			$html .= '		<div class="content">' . PHP_EOL;
			$html .= '			<a class="author">' . $comment->user->getUsername() . '</a>' . PHP_EOL;
			$html .= '			<div class="metadata">' . PHP_EOL;
			$html .= '				<span class="date">' . $comment->timeToString() . '</span>' . PHP_EOL;
			$html .= '			</div>' . PHP_EOL;
			$html .= '			<div class="text">' . PHP_EOL;
			$html .= '				' . $comment->getCommentText() . PHP_EOL;
			$html .= '			</div>' . PHP_EOL;
			$html .= '		</div>' . PHP_EOL;
			$html .= '	</div>' . PHP_EOL;
		}
		$html .= '</div>' . PHP_EOL;
		echo $html;
	}
?>

</div>
