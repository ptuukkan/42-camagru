/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   apiService.js                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/31 14:53:17 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/31 19:45:47 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */



export const sendPhoto = async (blob) => {
	const fd = new FormData();
	fd.append('files[]', blob);
	const response = await fetch('/edit/submit', {
		method: 'POST',
		headers: {
			'Content-Type': 'multipart/form-data'
		},
		body: fd
	});
	console.log(response);
}
