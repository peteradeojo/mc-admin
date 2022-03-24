<?php

namespace Database;

use Exception;
use mysqli;

class Database
{
	protected \mysqli $cxn;
	function __construct()
	{
	}

	// Connection
	function connect()
	{
		$cxn = new mysqli($_ENV['DBHOST'], $_ENV['DBUSER'], $_ENV['DBPASSWD'], $_ENV['DBNAME']);
		if ($cxn->error) {
			throw new Exception("Database connection failed. " . $cxn->error);
		}
		$this->cxn = $cxn;
		return true;
	}

	// Checks table existence in database
	protected function tableExists(string $table): bool | Exception
	{
		$sql = "SHOW TABLES LIKE '$table'";
		$query = $this->cxn->query($sql);
		$result = $query->fetch_array();
		if ($result) {
			return true;
		} else {
			if ($this->cxn->error) {
				return $this->cxn->error;
			}
			throw new Exception("Table '$table' does not exist.");
		}
	}

	// Retrieve info from Database table
	function select(string $table, string $rows = '*', string $where = null, string $orderby = null, int $limit = null, bool $distinct = false): array
	{
		try {
			if ($this->tableExists($table)) {
				$sql = "SELECT ";
				if ($distinct) {
					$sql .= "DISTINCT ";
				}
				$sql .= "$rows from $table";
				if ($where) {
					$sql .= " WHERE $where";
				}
				if ($orderby) {
					$sql .= " ORDER BY $orderby";
				}
				if ($limit) {
					$sql .= " LIMIT $limit";
				}
				$sql .= ";";
				// echo $sql;
				// return [];

				$query = $this->cxn->query($sql);
				if (!$query) {
					return [];
				}
				$result = $query->fetch_all(MYSQLI_ASSOC);
				return $result;
			}
		} catch (Exception $e) {
			//throw $th;
			throw new Exception($e->getMessage());
		}
	}

	function join(array $tables, array $join_types, string $table_rows = '*', string $where = null)
	{
		$sql = "SELECT $table_rows FROM $tables[0]";
		for ($i = 0; $i < count($tables) - 1; $i += 1) {
			$sql .= " {$join_types[$i]['type']} JOIN {$tables[$i + 1]} ON {$join_types[$i]['on']}";
		}
		if ($where) {
			$sql .= " WHERE $where";
		}
		$sql .= ";";

		// echo $sql;
		// return;

		$query = $this->cxn->query($sql);
		if (!$query) {
			throw new Exception($this->cxn->error);
		}
		$result = $query->fetch_all(MYSQLI_ASSOC);
		return $result;
	}

	// Insert data into tables
	function insert(array $table_values)
	{
		if (!$table_values) {
			throw new Exception('No data to insert');
		}
		$sql = "";
		foreach ($table_values as $table => $values) {
			$usablevalues = [];
			$sql .= "INSERT INTO $table (" . join(',', array_keys($values)) . ") VALUES (";
			for ($i = 0; $i < count(array_values($values)); $i += 1) {
				$usablevalues[] = (array_values($values)[$i] !== 0 and array_values($values)[$i] !== null and array_values($values)[$i] !== '') ? "'" . array_values($values)[$i] . "'" : 'null';
			}


			$sql .= join(',', $usablevalues) . ');';
		}

		// echo $sql;
		// return;

		if (count($table_values) > 1) {
			$query = $this->cxn->multi_query($sql);
		} else {
			$query = $this->cxn->query($sql);
		}
		while ($result = $this->cxn->next_result()) {
			if (!$result) {
				throw new Exception($this->cxn->error);
			}
		};
		return true;
	}

	function update(array $tables_values, string $where, bool $replaceInto = false)
	{
		// $sql = "START TRANSACTION;";
		$sql = '';
		foreach ($tables_values as $table => $values) {
			# code...
			if (!$replaceInto) {
				$sql .= "UPDATE $table SET ";
				for ($i = 0; $i < count(array_keys($values)); $i += 1) {
					$sql .= array_keys($values)[$i] . "=";
					// Account for null values to avoid errors
					$sql .=  (array_values($values)[$i] !== null and array_values($values)[$i] !== 0 and array_values($values)[$i] !== '') ? "'" . array_values($values)[$i] . "'" : 'null';
					if ($i < count(array_keys($values)) - 1) {
						$sql .= ",";
					}
				}
				$sql .= " WHERE $where;";
			} else {
				$sql .= "REPLACE INTO $table (" . join(',', array_keys($values)) . ") VALUES (";
				for ($i = 0; $i < count(array_keys($values)); $i += 1) {
					// Account for null values to avoid errors
					$sql .= (array_values($values)[$i] !== null and array_values($values)[$i] !== 0 and array_values($values)[$i] !== '') ? "'" . array_values($values)[$i] . "'" : 'null';
					if ($i < count(array_keys($values)) - 1) {
						$sql .= ",";
					}
				}
				$sql .= ");";
			}
		}

		// echo $sql . "<br><br>";
		// return;

		if (count($tables_values) > 1) {
			$query = $this->cxn->multi_query($sql);
		} else {
			$query = $this->cxn->query($sql);
		}
		while ($result = $this->cxn->next_result()) {
			if (!$result) {
				throw new Exception($this->cxn->error);
			}
		};
		return true;
	}

	function disconnect()
	{
		$this->cxn->close();
	}

	function __destruct()
	{
		$this->cxn->close();
	}
}
