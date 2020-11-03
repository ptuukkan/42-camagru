/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   gallery.js                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/11 21:10:35 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/03 20:09:25 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const addLike = (image) => {
	const formData = new FormData();
	formData.append("img_id", image.id);
	fetch('/addlike', {
		method: 'POST',
		body: formData
	}).then(response => {
		if (response.ok) {
			response.json().then(likes => {
				const likesText = image.querySelector(".num-of-likes");
				likesText.textContent = String(likes + 1);
			});
		} else {
			response.json().then(r => console.log(r));
		}
	});
}

const likeButtons = document.getElementsByClassName("like-button");
for (let likeButton of likeButtons) {
	likeButton.addEventListener("click", event => {
		addLike(event.target.parentElement.parentElement.parentElement);
	});
}

const toggleCommentButtons = document.getElementsByClassName("show-comments");
for (let toggleCommentButton of toggleCommentButtons) {
	toggleCommentButton.addEventListener("click", event => {
		const uiComments = event.target.parentElement.parentElement.querySelector(".ui.comments");
		const hiddenComments = uiComments.querySelector(".comments");
		if (hiddenComments) {
				hiddenComments.classList.toggle("collapsed");
			if (hiddenComments.classList.contains("collapsed")) {
				toggleCommentButton.textContent = "View all comments";
			} else {
				toggleCommentButton.textContent = "Hide comments";
			}
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

const checkCommentOver = (target) => {
	const parent = target.parentElement.parentElement;
	let commentOver = parent.querySelector(".comment-over");
	if (target.value.length >= 127) {
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

const createComment = (comment) => {
	const markup = `
			<a class="avatar">
				<img src="/public/img/user.png">
			</a>
			<div class="content">
				<a class="author">${comment.user.username}</a>
				<div class="metadata">
					<span class="date">${comment.comment_date}</span>
				</div>
				<div class="text">
					${comment.comment}
				</div>
			</div>
	`
	const div = document.createElement('div');
	div.classList.add("comment");
	div.innerHTML = markup;
	return div;
}

const uploadComment = async (comment, image) => {
	const formData = new FormData();
	formData.append("img_id", image.id);
	formData.append("comment", comment);
	fetch('/addcomment', {
		method: 'POST',
		body: formData
	}).then(response => {
		if (response.ok) {
			response.json().then(newComment => {
				const comments = image.querySelector(".ui.comments");
				comments.prepend(createComment(newComment));
			});
		}
	});
}

const commentInputs = document.getElementsByClassName("comment-input");
for (let commentInput of commentInputs) {
	commentInput.addEventListener("keyup", (event) => {
		if (event.keyCode === 13 && event.target.value.length < 127 &&
			event.target.value.length > 0) {
				commentInput.parentElement.classList.add("loading");
			uploadComment(event.target.value, commentInput.parentElement.parentElement.parentElement)
			.then(res => {
				event.target.value = "";
				commentInput.parentElement.classList.remove("loading");
			});
		} else {
			checkCommentOver(event.target);
		}
	});
}
