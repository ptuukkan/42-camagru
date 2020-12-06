/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   edit.js                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/27 21:09:45 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/12/06 20:08:21 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

import { enableStickers, disableStickers, getStickers, clearStickers } from './stickers.js';
import { addThumbCard } from './thumbnails.js';

const canvas = document.querySelector("#canvas");
const context = canvas.getContext("2d");
const photo = document.querySelector("#photo");
const video = document.querySelector("#video");
const takePhotoButton = document.querySelector("#takephoto");
const cancelPhotoButton = document.querySelector("#cancelphoto");
const webCamToggle = document.querySelector("#webcamtoggle");
const uploadButton = document.querySelector("#uploadphoto");
const uploadIcon = document.querySelector("#uploadicon");
const uploadInput = document.querySelector("#upload");
const saveButton = document.querySelector("#savephoto");
const loader = document.querySelector(".loader");
const imageFormats = document.querySelector("#imageformats");
let blobImage;
let streaming = false;
let height;
let width;
let mode;

const clearPhoto = () => {
	context.fillStyle = "#AAA";
	context.fillRect(0, 0, canvas.width, canvas.height);
	const data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
	photo.style.display = "none";
}

const setupWebCam = () => {
	video.addEventListener("canplay", (_event) => {
		width = document.querySelector("#webcam-container").clientWidth;
		if (loader.classList.contains("active")) {
			loader.classList.remove("active");
		}
		if (takePhotoButton.classList.contains("disabled")) {
			takePhotoButton.classList.remove("disabled");
		}
		if (!streaming) {
			height = video.videoHeight / (video.videoWidth / width);
			if (isNaN(height)) {
				height = width / (4 / 3);
			}
			video.setAttribute('width', width);
			video.setAttribute('height', height);
			canvas.setAttribute('width', width);
			canvas.setAttribute('height', height);
			streaming = true;
		}
	});

	takePhotoButton.addEventListener("click", (_event) => {
		if (width && height) {
		  canvas.width = width;
		  canvas.height = height;
		  context.drawImage(video, 0, 0, width, height);
		  const data = canvas.toDataURL('image/png');
		  photo.setAttribute('src', data);
		  photo.style.display = "";
		  video.style.display = "none";
		} else {
		  clearPhoto();
		}
		if (saveButton.classList.contains("disabled")) {
			saveButton.classList.remove("disabled");
		}
		takePhotoButton.style.display = "none";
		cancelPhotoButton.style.display = "";
	});

	cancelPhotoButton.addEventListener("click", (_event) => {
		cancelPhotoButton.style.display = "none";
		video.style.display = "";
		takePhotoButton.style.display = "";
		if (!saveButton.classList.contains("disabled")) {
			saveButton.classList.add("disabled");
		}
		clearPhoto();
	});
}

const webCamMode = () => {
	mode = 1;
	cancelPhotoButton.style.display = "none";
	uploadButton.style.display = "none";
	uploadIcon.style.display = "none";
	video.style.display = "";
	takePhotoButton.style.display = "";
	photo.removeAttribute("src");
	if (!loader.classList.contains("active")) {
		loader.classList.add("active");
	}
	imageFormats.style.display = "none";
	navigator.mediaDevices.getUserMedia({ video: true, audio: false })
		.then((stream) => {
			video.srcObject = stream;
			video.play();
		})
		.catch((_error) => {
			webCamToggle.checked = false;
			webCamToggle.disabled = true;
			return uploadMode();
		});

	enableStickers();
	clearPhoto();
}

const setupUpload = () => {
	uploadButton.addEventListener("click", (_event) => {
		document.querySelector("#upload").click();
	});

	uploadInput.addEventListener("change", (event) => {
		if (event.target.files && event.target.files[0]) {
			if (event.target.files[0].type !== "image/png" &&
				event.target.files[0].type !== "image/jpeg") {
					window.alert("Unsupported image type");
					uploadMode();
					return;
				}
			blobImage = URL.createObjectURL(event.target.files[0]);
			photo.setAttribute('src', blobImage);
			photo.style.display = "";
			uploadIcon.style.display = "none";
			if (saveButton.classList.contains("disabled")) {
				saveButton.classList.remove("disabled");
			}
			enableStickers();
		} else {
			photo.removeAttribute('src');
			photo.style.display = "none";
			uploadIcon.style.display = "";
			if (!saveButton.classList.contains("disabled")) {
				saveButton.classList.add("disabled");
			}
			disableStickers();
		}
	})
}

const uploadMode = () => {
	mode = 2;
	takePhotoButton.style.display = "none";
	cancelPhotoButton.style.display = "none";
	if (!takePhotoButton.classList.contains("disabled")) {
		takePhotoButton.classList.add("disabled");
	}
	if (!saveButton.classList.contains("disabled")) {
		saveButton.classList.add("disabled");
	}
	video.style.display = "none";
	video.srcObject = null;
	uploadButton.style.display = "";
	uploadIcon.style.display = "";
	if (loader.classList.contains("active")) {
		loader.classList.remove("active");
	}
	imageFormats.style.display = "";
	disableStickers();
	clearPhoto();
}

webCamToggle.addEventListener("click", (event) => {
	if (event.target.checked) {
		webCamMode();
	} else {
		uploadMode();
	}
});

const toBase64 = (file) => {
	const reader = new FileReader();
	reader.readAsDataURL(file);
	return new Promise(resolve => {
		reader.onloadend = () => {
			resolve(reader.result);
		}
	})
}

const getImageData = async () => {
	if (mode == 1) {
		return photo.getAttribute("src");
	}
	let data = await toBase64(uploadInput.files[0]);
	return data;
}

saveButton.addEventListener("click", (_event) => {
	saveButton.classList.add("loading");
	const stickers = getStickers();
	getImageData().then(data => {
		const formData = new FormData();
		formData.append("img_data", data);
		formData.append("img_width", photo.clientWidth);
		formData.append("stickers", stickers);
		fetch('/images', {
			method: 'POST',
			body: formData,
		})
		.then((response) => {
			if (response.ok) {
				let contentType = response.headers.get("content-type");
				if (contentType && contentType.includes("application/json")) {
					return response.json();
				}
			}
			throw new Error();
		})
		.then((image) => addThumbCard(image))
		.catch((_error) => {
		})
		.finally(() => {
			saveButton.classList.remove("loading");
			clearStickers();
			if (mode === 1) {
				webCamMode();
			} else {
				uploadMode();
			}
			if (!saveButton.classList.contains("disabled")) {
				saveButton.classList.add("disabled");
			}
			uploadInput.value = "";
		});
	});
});

document.addEventListener('DOMContentLoaded', (_event) => {
	setupUpload();
	setupWebCam();
	webCamMode();
}, false);



