<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   View.class.php                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/18 21:37:47 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/18 21:37:47 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

class View
{
	private static function _loggedIn()
	{
		return Application::$app->session->loggedIn;
	}

	private static function _printFieldErrors($field, $params)
	{
		$string = "";
		if (isset($params["errors"][$field])) {
			$string .= '<div class="ui error message">';
			foreach ($params["errors"][$field] as $error) {
				$string .= "<p>$error</p>";
			}
			$string .= '</div>';
		}
		return $string;
	}

	private static function _printImage($image)
	{
		ob_start();
		require "views/image.php";
		$html = ob_get_clean();
		echo $html;
	}

	private static function _printThumbnail($image)
	{
		ob_start();
		require "views/thumbnail.php";
		$html = ob_get_clean();
		echo $html;
	}

	private static function _printComments($comments)
	{
		if (empty($comments)) {
			return;
		}
		$size = count($comments);
		ob_start();
		require "views/comments.php";
		$html = ob_get_clean();
		echo $html;
	}

	private static function _printPagination($params)
	{
		$hasNext = false;
		$hasPrev = false;
		$html = "";
		$prevPage = $params["page"] - 1;
		$nextPage = $params["page"] + 1;

		if ($params["page"] > 1) {
			$hasPrev = true;
		}
		if ($params["page"] * 5 < $params["total"]) {
			$hasNext = true;
		}
		if ($hasPrev) {
			$html .= '<div class="ui pagination menu">' . PHP_EOL;
			$html .= '	<a href="/?page=' . $prevPage . '">' . PHP_EOL;
			$html .= '		<div class="item">Previous Page</div>' . PHP_EOL;
			$html .= '	</a>' . PHP_EOL;
			$html .= '</div>' . PHP_EOL;
		}
		if ($hasNext) {
			$html .= '<div class="ui pagination menu right floated">' . PHP_EOL;
			$html .= '	<a href="/?page=' . $nextPage . '">' . PHP_EOL;
			$html .= '		<div class="item">Next Page</div>' . PHP_EOL;
			$html .= '	</a>' . PHP_EOL;
			$html .= '</div>' . PHP_EOL;
		}
		echo $html;
	}

	public static function renderView($layout, $view, $params = [])
	{
		ob_start();
		require_once "views/layouts/" . $layout . ".php";
		$layout = ob_get_clean();
		ob_start();
		require_once "views/" . $view . ".php";
		$view = ob_get_clean();
		echo str_replace("{{view}}", $view, $layout);
	}

	public static function renderMessage($layout, $message)
	{
		ob_start();
		require_once "views/layouts/" . $layout . ".php";
		$layout = ob_get_clean();
		ob_start();
		require_once "views/message.php";
		$message = ob_get_clean();
		echo str_replace("{{view}}", $message, $layout);
	}
}
