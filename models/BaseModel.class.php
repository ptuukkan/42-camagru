<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   BaseModel.class.php                                :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: ptuukkan <ptuukkan@student.hive.fi>        +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2020/10/20 19:41:23 by ptuukkan          #+#    #+#             */
/*   Updated: 2020/10/20 19:41:23 by ptuukkan         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

abstract class BaseModel
{
	public static $verbose = false;

	public function __construct()
	{
		if (self::$verbose) {
			print(static::class . " instance constructed" . PHP_EOL);
		}
	}

	protected abstract function tableName();

	public static function findOne($filter)
	{
		$table = static::tableName();
		$fields = array_keys($filter);
		$fields = array_map(function($field) {
			return "$field = :$field";
		}, $fields);
		$sql = "SELECT * FROM " . $table . " WHERE " . implode(" AND ", $fields);
		$statement = Application::$db->prepare($sql);
		foreach ($filter as $field => $value) {
			$statement->bindValue(":$field", $value);
		}
		$statement->execute();
		return $statement->fetchObject(static::class);
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print(static::class . " instance destructed" . PHP_EOL);
		}
	}
}
