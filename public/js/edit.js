/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   edit.js                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/27 21:09:45 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/30 16:20:17 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const clearPhoto = () => {
	const canvas = document.querySelector("#canvas");
	const context = canvas.getContext("2d");
	const photo = document.querySelector("#photo");

	context.fillStyle = "#AAA";
	context.fillRect(0, 0, canvas.width, canvas.height);
	const data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
	photo.style.display = "none";
}

const setupWebCam = () => {
	const video = document.querySelector("#video");
	const canvas = document.querySelector("#canvas");
	const takePhotoButton = document.querySelector("#takephoto");
	const photo = document.querySelector("#photo");
	const width = document.querySelector("#webcam-container").clientWidth;
	let streaming = false;

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
		const context = canvas.getContext('2d');
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
	const video = document.querySelector("#video");
	const webCamToggle = document.querySelector("#webcamtoggle");
	const takePhotoButton = document.querySelector("#takephoto");
	const uploadButton = document.querySelector("#uploadphoto");
	const uploadIcon = document.querySelector("#uploadicon");

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
	const uploadInput = document.querySelector("#upload");
	const uploadButton = document.querySelector("#uploadphoto");
	const uploadIcon = document.querySelector("#uploadicon");
	const saveButton = document.querySelector("#savephoto");
	const photo = document.querySelector("#photo");

	uploadButton.addEventListener("click", (event) => {
		document.querySelector("#upload").click();
	});

	uploadInput.addEventListener("change", (event) => {
		if (event.target.files && event.target.files[0]) {
			photo.setAttribute('src', URL.createObjectURL(event.target.files[0]));
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
	const uploadIcon = document.querySelector("#uploadicon");
	const uploadButton = document.querySelector("#uploadphoto");
	const takePhotoButton = document.querySelector("#takephoto");
	const video = document.querySelector("#video");

	takePhotoButton.style.display = "none";
	video.style.display = "none";
	uploadButton.style.display = "";
	uploadIcon.style.display = "";
	clearPhoto();
}

document.querySelector("#webcamtoggle").addEventListener("click", (event) => {
	if (event.target.checked) {
		webCamMode();
	} else {
		uploadMode();
	}
})

setupUpload();
setupWebCam();
webCamMode();
