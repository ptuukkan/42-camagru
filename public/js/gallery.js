/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   gallery.js                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/11 21:10:35 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/12 18:18:23 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const likeButtons = document.getElementsByClassName("like-button");
for (let likeButton of likeButtons) {
	likeButton.addEventListener("click", event => {
		const likesText = event.target.parentElement.querySelector(".num-of-likes")
		const likes = Number(likesText.textContent);
		likesText.textContent = String(likes + 1);
	});
}

const toggleCommentButtons = document.getElementsByClassName("show-comments");
for (let toggleCommentButton of toggleCommentButtons) {
	toggleCommentButton.addEventListener("click", event => {
		const uiComments = event.target.parentElement.parentElement.querySelector(".ui.comments");
		const hiddenComments = uiComments.querySelector(".comments");
		hiddenComments.classList.toggle("collapsed");
		if (hiddenComments.classList.contains("collapsed")) {
			toggleCommentButton.textContent = "View all comments";
		} else {
			toggleCommentButton.textContent = "Hide comments";
		}
	});
}
