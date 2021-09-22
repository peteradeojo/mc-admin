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
		// print_r($result);
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
	function select(string $table, string $rows = '*', string $where = null): array | Exception
	{
		try {
			if ($this->tableExists($table)) {
				$sql = "SELECT $rows from $table";
				if ($where) {
					$sql .= " WHERE $where";
				}

				// echo $sql;
				// exit();

				$query = $this->cxn->query($sql);
				$result = $query->fetch_all(MYSQLI_ASSOC);
				// echo count($result);
				if (count($result) > 1) {
					return $result;
				} elseif (count($result) === 1) {
					return $result[0];
				} else {
					return [];
				}
			}
		} catch (Exception $e) {
			//throw $th;
			throw new Exception($e->getMessage());
		}
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
			// print_r($values);
			// echo "<br><br>";
			$sql .= "INSERT INTO $table (" . join(',', array_keys($values)) . ") VALUES (";
			for ($i = 0; $i < count(array_values($values)); $i += 1) {
				$usablevalues[] = "'" . array_values($values)[$i] . "'";
			}

			// print_r($usablevalues);

			$sql .= join(',', $usablevalues) . ');';
		}

		// echo $sql;
		// exit();
		if (count($table_values) > 1) {
			$query = $this->cxn->multi_query($sql);
		} else {
			$query = $this->cxn->query($sql);
		}
		if (!$query) {
			// echo $this->cxn->error;
			throw new Exception($this->cxn->error);
		}
		return true;
	}

	function update(array $tables_values, string $where)
	{
		$sql = "";
		foreach ($tables_values as $table => $values) {
			# code...
			$sql .= "UPDATE $table SET ";
			for ($i = 0; $i < count(array_keys($values)); $i += 1) {
				$sql .= array_keys($values)[$i] . "='" . array_values($values)[$i] . "'";
				if ($i < count(array_keys($values)) - 1) {
					$sql .= ",";
				}
			}
			$sql .= " WHERE $where;";
		}

		// echo $sql;
		// exit();
		if (count($tables_values) > 1) {
			$query = $this->cxn->multi_query($sql);
		} else {
			$query = $this->cxn->query($sql);
		}
		if (!$query) {
			throw new Exception($this->cxn->error);
		}
		return true;
	}

	function __destruct()
	{
		$this->cxn->close();
	}
}
