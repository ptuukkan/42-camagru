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
    <div class="content">
			<div class="right floated extra content">
        <div class="ui toggle checkbox">
          <input type="checkbox" name="webcam" id="webcamtoggle" checked>
          <label>Use WebCam</label>
        </div>
      </div>
		</div>
		<div class="image" id="webcam-container" style="text-align: center">
			<video id="video">Video stream not available.</video>
      <i class="upload icon huge" style="margin: 50px; display: none" id="uploadicon"></i>
      <img id="photo" style="display: none">
		</div>
		<div class="content" style="text-align: center">
			<input type="file" hidden id="upload" />
			<button class="ui button primary" id="uploadphoto" style="display: none">Upload photo</button>
			<button class="ui button primary" id="takephoto">Take photo</button>
      <button class="ui button primary disabled" id="savephoto">Save photo</button>
		</div>
	</div>
	<div class="four wide column centered">
	<div class="ui segment">
	<div class="ui divided items">
  <div class="item">
    <div class="image">
      <img src="/images/wireframe/image.png">
    </div>
  </div>
  <div class="item">
    <div class="image">
      <img src="/images/wireframe/image.png">
    </div>
  </div>
  <div class="item">
    <div class="image">
      <img src="/images/wireframe/image.png">
    </div>
  </div>
</div>
	</div>

	</div>
</div>
<canvas id="canvas" style="display: none">
</canvas>
<script type="module" src=/public/js/edit.js></script>

