/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   gallery.js                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/11 21:10:35 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/01 23:26:17 by ptuukkan         ###   ########.fr       */
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

const createCommentOverDiv = () => {
	const div = document.createElement('div');
	div.classList.add("extra");
	div.classList.add("content");
	const span = document.createElement('span');
	span.classList.add("right");
	span.classList.add("floated");
	span.classList.add("comment-over");
	div.appendChild(span);
	return div;
}

const commentInputs = document.getElementsByClassName("comment-input");
for (let commentInput of commentInputs) {
	commentInput.addEventListener("keyup", (event) => {
		console.log(event.target.value.length);
		if (event.keyCode === 13 && event.target.value.length < 127) {
			fetch('/')
		} else {
			const parent = commentInput.parentElement.parentElement;
			let commentOver = parent.querySelector(".comment-over");
			if (event.target.value.length >= 127) {
				if (!commentOver) {
					parent.appendChild(createCommentOverDiv());
				}
				commentOver = parent.querySelector(".comment-over");
				commentOver.textContent = `${event.target.value.length}/127`;
			} else {
				if (commentOver) {
					commentOver.parentElement.remove();
				}
			}
		}
	});
}
