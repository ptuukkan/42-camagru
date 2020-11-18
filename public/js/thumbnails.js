/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   thumbnails.js                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/12 22:14:44 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/18 17:50:35 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const delThumbCard = (id) => {
	const thumbCard = document.getElementById(id);
	if (thumbCard) {
		thumbCard.remove();
	}
}

const deleteEvent = async (id) => {
	const formData = new FormData();
	formData.append("img_id", id);
	try {
		const response = await fetch('/deleteimage', {
			method: 'POST',
			body: formData,
		})
		if (response.ok) {
			let contentType = response.headers.get("content-type");
			if (contentType && contentType.includes("application/json")) {
				const img_id = await response.json();
				delThumbCard(img_id);
				return;
			}
		}
		throw new Error("Something went wrong with delete request");
	} catch (error) {
		console.log(error);
	}
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
	div.id = image.img_id;
	div.innerHTML = markup;
	div.querySelector("button").addEventListener("click", (event) => {
		if (!event.target.classList.contains("loading")) {
			event.target.classList.add("loading");
		}
		deleteEvent(thumbCard.id).then(() => {
			if (event.target.classList.contains("loading")) {
				event.target.classList.remove("loading");
			}
		});
	});
	const thumbnailList = document.querySelector('.ui.one.cards');
	thumbnailList.prepend(div);
	thumbnailList.parentElement.style.display = "";
}

const thumbCards = document.getElementsByClassName("thumbcard");
for (let thumbCard of thumbCards) {
	const deleteButton = thumbCard.querySelector("button");
	deleteButton.addEventListener("click", (event) => {
		if (!event.target.classList.contains("loading")) {
			event.target.classList.add("loading");
		}
		deleteEvent(thumbCard.id).then(() => {
			if (event.target.classList.contains("loading")) {
				event.target.classList.remove("loading");
			}
		});
	});
}
