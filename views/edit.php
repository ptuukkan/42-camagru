<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   edit.php                                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/27 21:08:39 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/27 21:08:39 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>

<div class="ui grid">
	<div class="ui fluid card ten wide column centered">
		<div class="image" id="webcam-container">
			<video id="video">Video stream not available.</video>
		</div>
		<div class="content" style="text-align: center">
			<button class="ui button primary" id="takephoto">Take photo</button>
		</div>
	</div>
</div>

<div class="camera">
    
    
</div>
<canvas id="canvas" style="display: none">
  </canvas>
  <div class="output">
    <img id="photo" alt="The screen capture will appear in this box.">
  </div>
<script src=/public/js/edit.js></script>

