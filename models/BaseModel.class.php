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

	public static function findMany($fields = [], $filter = [])
	{
		$db = Application::$app->db;
		$table = static::_tableName();
		if (empty($fields)) {
			$fields = "*";
		} else {
			$fields = implode(", ", $fields);
		}
		$sql = "SELECT " . $fields . " FROM $table";
		if (!empty($filter)) {
			$whereFilter = array_map(function($field) {
				return "$field = :$field";
			}, array_keys($filter));
			$sql .= " WHERE " . implode(" AND ", $whereFilter);
		}
		$db->prepare($sql);
		if (!empty($filter)) {
			foreach ($filter as $field => $value) {
				$db->bindValue(":$field", $value);
			}
		}
		$db->execute();
		if ($fields === "*") {
			return $db->fetchAll(static::class);
		}
		return $db->fetchAll();
	}

	public static function findOne($filter, $fields = [])
	{
		$db = Application::$app->db;
		$table = static::_tableName();
		$whereFilter = array_map(function($field) {
			return "$field = :$field";
		}, array_keys($filter));
		if (empty($fields)) {
			$fields = "*";
		} else {
			$fields = implode(", ", $fields);
		}
		$sql = "SELECT " . $fields . " FROM " . $table;
		$sql .= " WHERE " . implode(" AND ", $whereFilter);
		$db->prepare($sql);
		foreach ($filter as $field => $value) {
			$db->bindValue(":$field", $value);
		}
		if ($fields === "*") {
			return $db->fetch(static::class);
		}
		return $db->fetch();
	}

	protected function _insert()
	{
		$db = Application::$app->db;
		$table = static::_tableName();
		$properties = static::_propertiesInDb();
		$params = array_map(function($property) {
			return ":$property";
		}, $properties);
		$sql = "INSERT INTO $table (" . implode(", ", $properties);
		$sql .= ") VALUES (" . implode(", ", $params) . ")";
		$db->prepare($sql);
		foreach ($properties as $property) {
			$db->bindValue(":$property", $this->{$property});
		}
		$this->id = $db->execute();
	}

	protected function _update()
	{
		$db = Application::$app->db;
		$table = static::_tableName();
		$properties = static::_propertiesInDb();
		$params = array_map(function($property) {
			return "$property=:$property";
		}, $properties);
		$sql = "UPDATE $table SET " . implode(", ", $params) . " WHERE id=:id";
		$db->prepare($sql);
		foreach ($properties as $property) {
			$db->bindValue(":$property", $this->{$property});
		}
		$db->bindValue(":id", $this->id);
		$db->execute();
	}

	public function save()
	{
		if ($this->id) {
			$this->_update();
		} else {
			$this->_insert();
		}
	}

	public function delete()
	{
		$db = Application::$app->db;
		$table = static::_tableName();
		$sql = "DELETE FROM $table WHERE id=:id";
		$db->prepare($sql);
		$db->bindValue(":id", $this->id);
		$db->execute();
	}
}
