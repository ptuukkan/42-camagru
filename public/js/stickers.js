/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   stickers.js                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/11/12 16:13:12 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/12/08 17:24:44 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

import { mode } from './edit.js';

const stickers = document.getElementsByClassName("sticker");
const takePhotoButton = document.querySelector("#takephoto");
let stickersEnabled;
let selectedStickers = new Array();

for (let sticker of stickers) {
	sticker.querySelector("img").addEventListener("click", (_event) => {
		if (stickersEnabled) {
			sticker.querySelector("input").checked ^= 1;
			const event = new Event("change");
			sticker.querySelector("input").dispatchEvent(event);
		}
	});
	sticker.querySelector("input").addEventListener("change", (event) => {
		if (event.target.checked === true) {
			addSticker(event.target.id);
		} else {
			removeSticker(event.target.id);
		}
	});
}

export const disableStickers = () => {
	for (let sticker of stickers) {
		if (!sticker.classList.contains("disabled")) {
			sticker.classList.add("disabled");
			sticker.querySelector("input").disabled = true;
		}
	}
	stickersEnabled = false;
}

export const enableStickers = () => {
	for (let sticker of stickers) {
		if (sticker.classList.contains("disabled")) {
			sticker.classList.remove("disabled");
		}
		sticker.querySelector("input").disabled = false;
	}
	stickersEnabled = true;
}

export const clearStickers = () => {
	for (let sticker of stickers) {
		if (sticker.querySelector("input").checked) {
			removeSticker(sticker.querySelector("input").id);
			sticker.querySelector("input").checked = false;
		}
	}
}

const addSticker = (id) => {
	const webcam = document.querySelector("#webcam-container");
	const sticker = document.querySelector(`#${id}`);
	const stickerImage = sticker.parentElement.querySelector("img");
	const clone = stickerImage.cloneNode();
	const photo = document.querySelector("#photo");
	clone.id = `${id}-clone`;
	clone.classList.remove("ui", "image");
	clone.style.width = stickerImage.width + "px";
	clone.style.height = stickerImage.height + "px";
	if (mode === 2 && photo.height < clone.height) {
		clone.style.height = photo.height + "px";
		clone.style.width = photo.height * (stickerImage.width / stickerImage.height) + "px";
	}
	clone.style.position = "absolute";
	clone.style.zIndex = 1000;
	clone.style.top = "0px";
	clone.style.left = "0px";
	clone.draggable = false;

	selectedStickers.push(clone.id);

	clone.addEventListener("mousedown", (event) => {
		if (event.button === 2) {
			return false;
		}
		const shiftX = event.clientX - clone.getBoundingClientRect().left;
 		const shiftY = event.clientY - clone.getBoundingClientRect().top;

		const moveAt = (x, y) => {
			clone.style.left = x - shiftX + "px";
			clone.style.top = y - shiftY + "px";
		}

		const mouseMoveEvent = (event) => {
			const webcamRect = webcam.getBoundingClientRect();
			const cloneRect = clone.getBoundingClientRect();

			const x = event.clientX - webcamRect.left;
			const y = event.clientY - webcamRect.top;
			moveAt(x, y);
			if (cloneRect.top < webcamRect.top || cloneRect.left < webcamRect.left ||
				cloneRect.right > webcamRect.right || cloneRect.bottom > webcamRect.bottom) {
				clone.style.opacity = "0.4";
			} else {
				clone.style.opacity = "1";
			}
		}

		document.addEventListener("mousemove", mouseMoveEvent);
		clone.addEventListener("click", _event => {
			document.removeEventListener("mousemove", mouseMoveEvent);
		});

		document.onmouseup = () => {
			document.removeEventListener("mousemove", mouseMoveEvent);
			document.onmouseup = null;
			const cloneRect = clone.getBoundingClientRect();
			const webcamRect = webcam.getBoundingClientRect();
			if (cloneRect.top < webcamRect.top) {
				clone.style.top = "0px";
			}
			if (cloneRect.left < webcamRect.left) {
				clone.style.left = "0px";
			}
			if (cloneRect.right > webcamRect.right) {
				clone.style.left = webcamRect.width - cloneRect.width + "px";
			}
			if (cloneRect.bottom > webcamRect.bottom) {
				clone.style.top = webcamRect.height - cloneRect.height + "px";
			}
			clone.style.opacity = "1";
		}

	});

	clone.addEventListener("touchstart", (event) => {
		if (event.button === 2) {
			return false;
		}
		const shiftX = event.touches[0].clientX - clone.getBoundingClientRect().left;
 		const shiftY = event.touches[0].clientY - clone.getBoundingClientRect().top;

		const moveAt = (x, y) => {
			clone.style.left = x - shiftX + "px";
			clone.style.top = y - shiftY + "px";;
		}

		const touchMoveEvent = (event) => {
			event.preventDefault();
			const webcamRect = webcam.getBoundingClientRect();
			const cloneRect = clone.getBoundingClientRect();

			const x = event.touches[0].clientX - webcamRect.left;
			const y = event.touches[0].clientY - webcamRect.top;
			moveAt(x, y);
			if (cloneRect.top < webcamRect.top || cloneRect.left < webcamRect.left ||
				cloneRect.right > webcamRect.right || cloneRect.bottom > webcamRect.bottom) {
				clone.style.opacity = "0.4";
			} else {
				clone.style.opacity = "1";
			}
		}

		document.addEventListener("touchmove", touchMoveEvent, {passive: false});
		clone.addEventListener("click", _event => {
			document.removeEventListener("touchmove", touchMoveEvent);
		});

		document.ontouchend = () => {
			document.removeEventListener("touchmove", touchMoveEvent);
			document.ontouchend = null;
			const cloneRect = clone.getBoundingClientRect();
			const webcamRect = webcam.getBoundingClientRect();
			if (cloneRect.top < webcamRect.top) {
				clone.style.top = "0px";
			}
			if (cloneRect.left < webcamRect.left) {
				clone.style.left = "0px";
			}
			if (cloneRect.right > webcamRect.right) {
				clone.style.left = webcamRect.width - cloneRect.width + "px";
			}
			if (cloneRect.bottom > webcamRect.bottom) {
				clone.style.top = webcamRect.height - cloneRect.height + "px";
			}
			clone.style.opacity = "1";
		}

	});
	webcam.appendChild(clone);

	if (mode === 1 && selectedStickers.length !== 0) {
		if (takePhotoButton.classList.contains("disabled")) {
			takePhotoButton.classList.remove("disabled");
		}
	}
}

const removeSticker = (id) => {
	const clone = document.querySelector(`#${id}-clone`);
	selectedStickers = selectedStickers.filter(a => a !== clone.id);
	clone.remove();

	if (mode === 1 && selectedStickers.length === 0) {
		if (!takePhotoButton.classList.contains("disabled")) {
			takePhotoButton.classList.add("disabled");
		}
	}
}


export const getStickers = () => {
	const stickers = selectedStickers.map(s => {
		const sticker = document.querySelector(`#${s}`);
		return {
			id: s.replace("-clone", ""),
			width: sticker.width,
			height: sticker.height,
			offsetLeft: sticker.offsetLeft,
			offsetTop: sticker.offsetTop
		};
	});
	return (JSON.stringify(stickers));
}
