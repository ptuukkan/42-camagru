/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   thumbnails.js                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/12 22:14:44 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/12 22:18:52 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const delThumbCard = (id) => {
	const thumbCard = document.getElementById(id);
	if (thumbCard) {
		thumbCard.remove();
	}
}

const deleteEvent = (id) => {
	const formData = new FormData();
	formData.append("img_id", id);
	fetch('/deleteimage', {
		method: 'POST',
		body: formData,
	}).then((response) => {
		response.json().then(img_id => {
			if (response.ok) {
				delThumbCard(img_id);
			}
		});
	});
}

export const addThumbCard = (image) => {
	const markup = `
		<div class="image">
			<img src="${image.img_path}">
		</div>
		<div class="extra content" style="text-align: center">
			<button class="ui button negative">Delete</button>
		</div>
	`;
	const div = document.createElement('div');
	div.classList.add("card", "thumbCard");
	div.id = image.id;
	div.innerHTML = markup;
	div.querySelector("button").addEventListener("click", (event) => {
		deleteEvent(image.img_id);
	});
	const thumbnailList = document.querySelector('.ui.one.cards');
	thumbnailList.prepend(div);
	thumbnailList.parentElement.style.display = "";
}

const thumbCards = document.getElementsByClassName("thumbcard");
for (let thumbCard of thumbCards) {
	const deleteButton = thumbCard.querySelector("button");
	deleteButton.addEventListener("click", (event) => {
		deleteEvent(thumbCard.id);
	});
}
