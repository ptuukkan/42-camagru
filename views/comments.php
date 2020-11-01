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
			<a class="author"><?= $comments[0]["user"]["username"] ?></a>
			<div class="metadata">
				<span class="date">20 minutes ago</span>
			</div>
			<div class="text">
				<?= $comments[0]["comment"] ?>
			</div>
		</div>
	</div>
	<?php
		if (count($comments) > 1) {
			$html = '<div class="collapsed comments">' . PHP_EOL;
		}
		foreach (array_slice($comments, 1) as $comment) {
			$html .= '	<div class="comment">' . PHP_EOL;
			$html .= '		<a class="avatar">' . PHP_EOL;
			$html .= '			<img src="/public/img/user.png">' . PHP_EOL;
			$html .= '		</a>' . PHP_EOL;
			$html .= '		<div class="content">' . PHP_EOL;
			$html .= '			<a class="author">' . $comment["user"]["username"] . '</a>' . PHP_EOL;
			$html .= '			<div class="metadata">' . PHP_EOL;
			$html .= '				<span class="date">1 day ago</span>' . PHP_EOL;
			$html .= '			</div>' . PHP_EOL;
			$html .= '			<div class="text">' . PHP_EOL;
			$html .= '				' . $comment["comment"] . PHP_EOL;
			$html .= '			</div>' . PHP_EOL;
			$html .= '		</div>' . PHP_EOL;
			$html .= '	</div>' . PHP_EOL;
		}
		$html .= '</div>' . PHP_EOL;
	?>
</div>
