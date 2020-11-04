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
	protected abstract function _tableName();

	protected abstract function _propertiesInDb();

	public static function findMany($fields, $filter = [])
	{
		$table = static::_tableName();

		$sql = "SELECT " . implode(", ", $fields) . " FROM $table";
		if (!empty($filter)) {
			$filterPlaceholders = array_map(function($field) {
				return "$field = :$field";
			}, array_keys($filter));
			$sql .= " WHERE " . implode(" AND ", $filterPlaceholders);
		}
		$statement = Application::$app->db->prepare($sql);
		if (!empty($filter)) {
			foreach ($filter as $field => $value) {
				if (is_bool($value)) {
					$statement->bindValue(":$field", $value, PDO::PARAM_BOOL);
				} else if (is_int($value)) {
					$statement->bindValue(":$field", $value, PDO::PARAM_INT);
				} else {
					$statement->bindValue(":$field", $value);
				}
			}
		}
		$statement->execute();
		return $statement->fetchAll();
	}

	public static function findOne($filter, $fields = [])
	{
		$table = static::_tableName();
		$filterPlaceholders = array_map(function($field) {
			return "$field = :$field";
		}, array_keys($filter));
		if (empty($fields)) {
			$selectFilter = "*";
		} else {
			$selectFilter = implode(", ", $fields);
		}
		$sql = "SELECT " . $selectFilter . " FROM " . $table;
		$sql .= " WHERE " . implode(" AND ", $filterPlaceholders);
		$statement = Application::$app->db->prepare($sql);
		foreach ($filter as $field => $value) {
			if (is_bool($value)) {
				$statement->bindValue(":$field", $value, PDO::PARAM_BOOL);
			} else if (is_int($value)) {
				$statement->bindValue(":$field", $value, PDO::PARAM_INT);
			} else {
				$statement->bindValue(":$field", $value);
			}
		}
		$statement->execute();
		if (empty($fields)) {
			return $statement->fetchObject(static::class);
		}
		return $statement->fetch();
	}

	public function insert()
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
