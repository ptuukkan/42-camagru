<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   gallery.php                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/08 22:37:05 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/08 22:37:05 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */
?>
<div class="ui grid">
	<div class="ui fluid card ten wide column centered">
		<div class="content">
			<div class="right floated meta">11/10/2020 - 19:34</div>
				<img class="ui avatar image" src="/public/img/user.png"> Elliot
		</div>
		<div class="image">
			<img src="/public/img/batman2.jpg">
		</div>
		<div class="content">
			<span class="right floated">
				<i class="heart outline like icon like-button"></i>
				<span class="num-of-likes">17</span> likes
			</span>
			<i class="comment icon"></i>
			3 comments
		</div>
		<div class="extra content">
			<div class="ui large transparent left icon input">
				<i class="comment outline icon"></i>
				<input type="text" placeholder="Add Comment...">
			</div>
		</div>
		<div class="ui comments">
			<div class="comment">
				<a class="avatar">
					<img src="/public/img/user.png">
				</a>
				<div class="content">
					<a class="author">Christian Rocha</a>
					<div class="metadata">
						<span class="date">20 minutes ago</span>
					</div>
					<div class="text">
						I'm very interested in this motherboard. Do you know if it'd work in a Intel LGA775 CPU socket?
					</div>
				</div>
			</div>
			<div class="collapsed comments">
				<div class="comment">
					<a class="avatar">
						<img src="/public/img/user.png">
					</a>
					<div class="content">
						<a class="author">Elliot Fu</a>
						<div class="metadata">
							<span class="date">1 day ago</span>
						</div>
						<div class="text">
							No, it wont
						</div>
					</div>
				</div>
				<div class="comments">
					<div class="comment">
						<a class="avatar">
							<img src="/public/img/user.png">
						</a>
						<div class="content">
							<a class="author">Jenny Hess</a>
							<div class="metadata">
								<span class="date">2 day ago</span>
							</div>
							<div class="text">
								Maybe it would.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="extra content">
   			<a class="show-comments">
      			View all comments
    		</a>
		</div>
	</div>
	<div class="ui fluid card ten wide column centered">
		<div class="content">
			<div class="right floated meta">11/10/2020 - 19:34</div>
				<img class="ui avatar image" src="/public/img/user.png"> Elliot
		</div>
		<div class="image">
			<img src="/public/img/batman2.jpg">
		</div>
		<div class="content">
			<span class="right floated">
				<i class="heart outline like icon like-button"></i>
				<span class="num-of-likes">35</span> likes
			</span>
			<i class="comment icon"></i>
			3 comments
		</div>
		<div class="extra content">
			<div class="ui large transparent left icon input">
				<i class="comment outline icon"></i>
				<input type="text" placeholder="Add Comment...">
			</div>
		</div>
		<div class="ui comments">
			<div class="comment">
				<a class="avatar">
					<img src="/public/img/user.png">
				</a>
				<div class="content">
					<a class="author">Christian Rocha</a>
					<div class="metadata">
						<span class="date">20 minutes ago</span>
					</div>
					<div class="text">
						I'm very interested in this motherboard. Do you know if it'd work in a Intel LGA775 CPU socket?
					</div>
				</div>
			</div>
			<div class="collapsed comments">
				<div class="comment">
					<a class="avatar">
						<img src="/public/img/user.png">
					</a>
					<div class="content">
						<a class="author">Elliot Fu</a>
						<div class="metadata">
							<span class="date">1 day ago</span>
						</div>
						<div class="text">
							No, it wont
						</div>
					</div>
				</div>
				<div class="comments">
					<div class="comment">
						<a class="avatar">
							<img src="/public/img/user.png">
						</a>
						<div class="content">
							<a class="author">Jenny Hess</a>
							<div class="metadata">
								<span class="date">2 day ago</span>
							</div>
							<div class="text">
								Maybe it would.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="extra content">
   			<a class="show-comments">
      			View all comments
    		</a>
		</div>
	</div>
	<div class="ten wide column centered">
		<div class="ui pagination menu right floated">
			<a href="/?page=1">
				<div class="item active">1</div>
			</a>
			<a href="/?page=2">
				<div class="item">2</div>
			</a>
			<a href="/?page=3">
				<div class="item">3</div>
			</a>
		</div>
	</div>
</div>
<script src=/public/js/gallery.js></script>


