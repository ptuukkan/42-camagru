/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   gallery.js                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/11 21:10:35 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/11 21:43:21 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

const likeButtons = document.getElementsByClassName("like-button");
for (let likeButton of likeButtons) {
	likeButton.addEventListener("click", event => {
		const likesText = event.target.parentElement.querySelector(".num-of-likes")
		const likes = Number(likesText.textContent);
		likesText.textContent = String(likes + 1);
	})
}

