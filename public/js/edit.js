/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   edit.js                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/27 21:09:45 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/27 22:18:37 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const clearPhoto = (canvas, photo) => {
	const context = canvas.getContext("2d");
	context.fillStyle = "#EEE";
	context.fillRect(0, 0, canvas.width, canvas.height);
	const data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
}

const runWebcam = () => {
	const width = document.querySelector("#webcam-container").clientWidth;
	console.log(width);
	let height = null;
	const video = document.querySelector("#video");
	const canvas = document.querySelector("#canvas");
	const photo = document.querySelector("#photo");
	const takePhotoButton = document.querySelector("#takephoto");
	let streaming = false;

	console.log("getting user media");
	navigator.mediaDevices.getUserMedia({ video: true, audio: false })
		.then((stream) => {
			video.srcObject = stream;
			video.play();
		})
		.catch((error) => {
			console.log("error:" + error);
		});
		
	console.log("adding canplay event listener");
	video.addEventListener("canplay", (event) => {
		console.log("canplay listener triggered");

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
		
	}, false);

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
	
	clearPhoto(canvas, photo);
}

runWebcam();

