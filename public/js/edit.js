/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   edit.js                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/27 21:09:45 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/11/01 14:52:22 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

import { sendPhoto } from "/public/js/apiService.js";

const canvas = document.querySelector("#canvas");
const context = canvas.getContext("2d");
const photo = document.querySelector("#photo");
const video = document.querySelector("#video");
const takePhotoButton = document.querySelector("#takephoto");
const width = document.querySelector("#webcam-container").clientWidth;
const webCamToggle = document.querySelector("#webcamtoggle");
const uploadButton = document.querySelector("#uploadphoto");
const uploadIcon = document.querySelector("#uploadicon");
const uploadInput = document.querySelector("#upload");
const saveButton = document.querySelector("#savephoto");
let blobImage;
let streaming = false;

const clearPhoto = () => {
	context.fillStyle = "#AAA";
	context.fillRect(0, 0, canvas.width, canvas.height);
	const data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
	photo.style.display = "none";
}

const setupWebCam = () => {
	console.log("adding canplay event listener");
	video.addEventListener("canplay", (event) => {
		console.log("canplay listener triggered");
		if (!streaming) {
			console.log("not streaming");
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

	console.log("adding take photo event listener");
	takePhotoButton.addEventListener("click", (event) => {
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
		event.preventDefault();
	})
}

const webCamMode = () => {
	console.log("webcam mode");

	uploadButton.style.display = "none";
	uploadIcon.style.display = "none";
	video.style.display = "";
	takePhotoButton.style.display = "";

	console.log("getting user media");
	navigator.mediaDevices.getUserMedia({ video: true, audio: false })
		.then((stream) => {
			video.srcObject = stream;
			video.play();
		})
		.catch((error) => {
			webCamToggle.checked = false;
			webCamToggle.disabled = true;
			return uploadMode();
		});

	clearPhoto();
}

const setupUpload = () => {
	uploadButton.addEventListener("click", (event) => {
		document.querySelector("#upload").click();
	});

	uploadInput.addEventListener("change", (event) => {
		if (event.target.files && event.target.files[0]) {
			blobImage = URL.createObjectURL(event.target.files[0]);
			photo.setAttribute('src', blobImage);
			photo.style.display = "";
			uploadIcon.style.display = "none";
			if (saveButton.classList.contains("disabled")) {
				saveButton.classList.remove("disabled");
			}
		}
	})
}

const uploadMode = () => {
	console.log("upload mode");

	takePhotoButton.style.display = "none";
	video.style.display = "none";
	video.srcObject = null;
	uploadButton.style.display = "";
	uploadIcon.style.display = "";
	clearPhoto();
}

webCamToggle.addEventListener("click", (event) => {
	if (event.target.checked) {
		webCamMode();
	} else {
		uploadMode();
	}
});

saveButton.addEventListener("click", (event) => {
	let blobImageData;
	fetch(blobImage).then(response => response.blob()).then(blob => {
		blobImageData = blob;
	})

	const formData = new FormData();
	formData.append('data', blobImageData);
	console.dir(formData);

	// const files = uploadInput.files;
	// const formData = new FormData();

	// for (let i = 0; i < files.length; i++) {
	// 	formData.append('files[]', files[i]);
	// }
	// formData.append('file', uploadInput.files[0]);
	fetch('/edit/submit', {
		method: 'POST',
		body: formData,
	}).then((response) => (response.json()).then(data => console.log(data)));
})


setupUpload();
setupWebCam();
webCamMode();
