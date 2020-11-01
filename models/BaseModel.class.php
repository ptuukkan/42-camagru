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

	protected abstract function _tableName();

	protected abstract function _propertiesInDb();

	public static function findAll()
	{
		$table = static::_tableName();
		$sql = "SELECT * FROM " . $table;
		$statement = Application::$app->db->prepare($sql);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
	}

	public static function findOne($filter)
	{
		$table = static::_tableName();
		$fields = array_keys($filter);
		$fields = array_map(function($field) {
			return "$field = :$field";
		}, $fields);
		$sql = "SELECT * FROM " . $table . " WHERE " . implode(" AND ", $fields);
		$statement = Application::$app->db->prepare($sql);
		foreach ($filter as $field => $value) {
			$statement->bindValue(":$field", $value);
		}
		$statement->execute();
		return $statement->fetchObject(static::class);
	}

	protected function _insert()
	{
		$table = static::_tableName();
		$properties = static::_propertiesInDb();
		$valuePlaceholders = array_map(function($property) {
			return ":$property";
		}, $properties);
		$sql = "INSERT INTO $table (" . implode(", ", $properties) .
			") VALUES (" . implode(", ", $valuePlaceholders) . ")";
		$statement = Application::$app->db->prepare($sql);
		foreach ($properties as $property) {
			if (is_bool($this->{$property})) {
				$statement->bindValue(":$property", $this->{$property}, PDO::PARAM_BOOL);
			} else if (is_int($this->{$property})) {
				$statement->bindValue(":$property", $this->{$property}, PDO::PARAM_INT);
			} else {
				$statement->bindValue(":$property", $this->{$property});
			}
		}
		$statement->execute();
		return Application::$app->db->lastInsertId();
	}

	protected function _update($id, $properties)
	{
		$table = static::_tableName();
		$valuePlaceholders = array_map(function($property) {
			return "$property=:$property";
		}, $properties);
		$sql = "UPDATE $table SET " . implode(", ", $valuePlaceholders) . " WHERE id=:id";
		$statement = Application::$app->db->prepare($sql);
		foreach ($properties as $property) {
			if (is_bool($this->{$property})) {
				$statement->bindValue(":$property", $this->{$property}, PDO::PARAM_BOOL);
			} else if (is_int($this->{$property})) {
				$statement->bindValue(":$property", $this->{$property}, PDO::PARAM_INT);
			} else {
				$statement->bindValue(":$property", $this->{$property});
			}
		}
		$statement->bindValue(":id", $id, PDO::PARAM_INT);
		$statement->execute();
	}

	protected function _delete($id)
	{
		$table = static::_tableName();
		$sql = "DELETE FROM $table WHERE id=:id";
		$statement = Application::$app->db->prepare($sql);
		$statement->bindValue(":id", $id, PDO::PARAM_INT);
		$statement->execute();
	}

	public function __destruct()
	{
		if (self::$verbose) {
			print(static::class . " instance destructed" . PHP_EOL);
		}
	}
}
