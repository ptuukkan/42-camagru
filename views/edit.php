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
	<div class="three wide column centered">
		<div class="ui segment">
			<div class="ui form">
				<div class="field">
					<div class="ui checkbox sticker">
						<input type="checkbox" id="beer" name="sticker">
						<label>
							<img class="ui image" src="/public/img/stickers/beer.png">
						</label>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox sticker">
						<input type="checkbox" id="crown" name="sticker">
						<label>
							<img class="ui image" src="/public/img/stickers/crown.png">
						</label>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox sticker">
						<input type="checkbox" id="fire" name="sticker">
						<label>
							<img class="ui image" src="/public/img/stickers/fire.png">
						</label>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox sticker">
						<input type="checkbox" id="poop" name="sticker">
						<label>
							<img class="ui image" src="/public/img/stickers/poop.png">
						</label>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox sticker">
						<input type="checkbox" id="zzz" name="sticker">
						<label>
							<img class="ui image" src="/public/img/stickers/zzz.png">
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ten wide column centered">
		<div class="ui fluid card">
			<div class="content">
				<div class="right floated extra content">
					<div class="ui toggle checkbox">
						<input type="checkbox" name="webcam" id="webcamtoggle" checked>
						<label>Use WebCam</label>
					</div>
				</div>
			</div>
			<div class="image" id="webcam-container" style="text-align: center">
				<div class="ui loader"></div>
				<video id="video"></video>
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
	</div>
	<div class="three wide column centered">
		<div class="ui segment" style="overflow: auto; max-height: 90vh; <?= (empty($params)) ? "display: none" : "" ?>">
			<div class="ui one cards">
				<?php
					foreach ($params as $image) {
					 	echo self::_printThumbnail($image);
					}
				?>
			</div>
		</div>
	</div>
</div>
<canvas id="canvas" style="display: none">
</canvas>
<script type="module" src=/public/js/edit.js></script>
<script type="module" src=/public/js/stickers.js></script>
<script type="module" src=/public/js/thumbnails.js></script>


