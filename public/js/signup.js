/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   signup.js                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/12 19:52:52 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/12 23:02:55 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const validateEmail = (emailInput) => {
	const emailRegExp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-]+$/;
	if (emailRegExp.test(emailInput.value)) {
		return true;
	} else return false;
}

const validateUsername = (usernameInput) => {
	const usernameRegExp = /[^a-zA-Z0-9]+/;
	if (usernameInput.value.length < 3 || usernameRegExp.test(usernameInput.value)) {
		return false;
	} else return true;
}

const validatePassword = (passwordInput) => {
	const capitalRegExp = /[A-Z]/;
	const numberRegExp = /[0-9]/;
	if (passwordInput.value.length < 8) return false;
	if (!capitalRegExp.test(passwordInput.value)) return false;
	if (!numberRegExp.test(passwordInput.value)) return false;
	return true;
}

const validateConfirmPw = (pwInput, confirmPwInput) => {
	if (confirmPwInput.value.length > 0 && confirmPwInput.value === pwInput.value) {
		return true;
	} else return false;
}

const buildTemplate = (message) => {
	const template = document.createElement("template");
	template.innerHTML = `
		<div class="ui error message">
   		 <p>${message}</p>
  		</div>
	`;
	return template;
}

const setErrorMessage = (target, message) => {
	const template = buildTemplate(message);
	target.parentElement.appendChild(template.content.cloneNode(true));
	const form = document.querySelector(".form");
	form.classList.add("error");
}

const validateForm = (target) => {
	if (!validateEmail(target.querySelector("#email"))) {
		setErrorMessage(target.querySelector("#email"), "Email address not valid");
		console.log("email not valid");
	}
	if (!validateUsername(target.querySelector("#username"))) {
		setErrorMessage(target.querySelector("#username"),
		"Username must be longer than 3 and contain only alphanumeric characters");
		console.log("username not valid");
	}
	if (!validatePassword(target.querySelector("#password"))) {
		setErrorMessage(target.querySelector("#password"),
		"Password must be longer than 8 and contain at least one uppercase letter and one number");
		console.log("password not valid");
	}
	if (!validateConfirmPw(target.querySelector("#password"), target.querySelector("#confirm-password"))) {
		setErrorMessage(target.querySelector("#confirm-password"),
		"Passwords do not match");
		console.log("confirm password not valid");
	}
}

const form = document.querySelector(".form");
form.addEventListener("submit", (event) => {
	event.preventDefault();
	if (form.classList.contains("error")) {
		form.classList.remove("error");
	}
	form.querySelectorAll(".ui.error.message").forEach(e => e.remove());
	if (validateForm(event.target)) {
		console.log("wepee");
	}
})
