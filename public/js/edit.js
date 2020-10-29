/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   edit.js                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/27 21:09:45 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/29 20:53:01 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const clearPhoto = (canvas, photo) => {
	const context = canvas.getContext("2d");
	context.fillStyle = "#AAA";
	context.fillRect(0, 0, canvas.width, canvas.height);
	const data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
}

const setupWebCam = () => {
	const video = document.querySelector("#video");
	const canvas = document.querySelector("#canvas");
	const takePhotoButton = document.querySelector("#takephoto");
	const photo = document.querySelector("#photo");
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
		} else {
		  clearPhoto(canvas, photo);
		}
		event.preventDefault();
	})
}

const webCamMode = () => {
	console.log("webcam mode");
	const video = document.querySelector("#video");
	const webCamToggle = document.querySelector("#webcamtoggle");

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

	//clearPhoto(canvas, photo);
}

const setupUpload = () => {
	const uploadInput = document.querySelector("#upload");
	const uploadButton = document.querySelector("#uploadphoto");
	const uploadIcon = document.querySelector("#uploadicon");
	const saveButton = document.querySelector("#savephoto");

	uploadButton.addEventListener("click", (event) => {
		document.querySelector("#upload").click();
	});

	uploadInput.addEventListener("change", (event) => {
		if (event.target.files && event.target.files[0]) {
			photo.setAttribute('src', URL.createObjectURL(event.target.files[0]));
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
	const takePhotoButton = document.querySelector("#takephoto");
	const video = document.querySelector("#video");
	const uploadButton = document.querySelector("#uploadphoto");

	uploadButton.style.display = "";
	video.style.display = "none";
	uploadIcon.style.display = "";
	takePhotoButton.style.display = "none";
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
