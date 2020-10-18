/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   login.js                                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/12 22:50:24 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/13 09:34:14 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const form = document.querySelector(".form");

const inputFields = form.querySelectorAll("input");
inputFields.forEach((inputField) => {
	inputField.addEventListener("input", (event) => {
		const submitButton = form.querySelector("#submit");
		const userField = form.querySelector("#username");
		const passField = form.querySelector("#password");
		if (submitButton.classList.contains("disabled")) {
			if (userField.value.length > 0 && passField.value.length > 0) {
				submitButton.classList.remove("disabled");
			}
		} else {
			if (userField.value.length === 0 || passField.value.length === 0) {
				submitButton.classList.add("disabled");
			}
		}
	});
});

form.addEventListener("submit", (event) => {
	event.preventDefault();
	if (form.classList.contains("error")) {
		form.classList.remove("error");
	}
})
