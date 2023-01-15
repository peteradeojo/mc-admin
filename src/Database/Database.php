<?php

namespace Database;

use Exception;
use mysqli;

class Database
{
	protected \mysqli|null $cxn;

	public function getClient()
	{
		return $this->cxn;
	}

	function __construct()
	{
		$this->cxn = null;
	}

	function connect()
	{
		if ($this->cxn != null) {
			return true;
		}

		$cxn = new mysqli($_ENV['MYSQLHOST'], $_ENV['MYSQLUSER'], $_ENV['MYSQLPASSWORD'], $_ENV['MYSQLDATABASE'], $_ENV['MYSQLPORT']);
		if ($cxn->error) {
			throw new Exception("Database connection failed. " . $cxn->error);
		}
		$this->cxn = $cxn;
		return true;


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
	function select(string $table, string $rows = '*', string $where = null, string $orderby = null, int $limit = null): array
	{
		try {
			if ($this->tableExists($table)) {
				$sql = "SELECT $rows from $table";
				if ($where) {
					$sql .= " WHERE $where";
				}
				if ($orderby) {
					$sql .= " ORDER BY $orderby";
				}
				if ($limit) {
					$sql .= " LIMIT $limit";
				}

				// echo $sql;
				// return [];

				$query = $this->cxn->query($sql);
				$result = $query->fetch_all(MYSQLI_ASSOC);
				return $result;
			}
		} catch (Exception $e) {
			//throw $th;
			throw new Exception($e->getMessage());
		}
	}

	function join(array $tables, array $join_types, string $table_rows = '*', string $where = null, string $orderby = null, $limit = 100)
	{
		$sql = "SELECT $table_rows FROM $tables[0]";
		for ($i = 0; $i < count($tables) - 1; $i += 1) {
			$sql .= " {$join_types[$i]['type']} JOIN {$tables[$i + 1]} ON {$join_types[$i]['on']}";
		}
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


		// throw new Exception($sql);

		$query = $this->cxn->query($sql);
		if (!$query) {
			throw new Exception($this->cxn->error);
		}
		$result = $query->fetch_all(MYSQLI_ASSOC);
		return $result;
	}

	// Insert data into tables
	function insert(array $table_values, $replaceInto = false)
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


			$sql .= join(',', $usablevalues) . ')';

			if ($replaceInto) {
				$sql .= " ON DUPLICATE KEY UPDATE ";
				for ($i = 0; $i < count(array_keys($values)); $i += 1) {
					$sql .= array_keys($values)[$i] . "=";
					// Account for null values
					$sql .= (array_values($values)[$i] !== 0 and array_values($values)[$i] !== null and array_values($values)[$i] !== '') ? "'" . array_values($values)[$i] . "'" : 'null';
					if ($i < count(array_keys($values)) - 1) {
						$sql .= ',';
					}
				}
			}

			$sql .= ';';
		}

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
		return $this->cxn->insert_id;
	}

	function update(array $tables_values, string $where, bool $replaceInto = false)
	{
		// $sql = "BEGIN;";
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
		if ($this->cxn != null) {
			$this->disconnect();
		}
	}

	function beginTransaction()
	{
		$this->cxn->query('BEGIN;');
	}

	function commit()
	{
		$this->cxn->query('COMMIT;');
	}

	function rollback()
	{
		$this->cxn->query('ROLLBACK;');
	}

	function lastId()
	{
		return $this->cxn->insert_id;
	}
}
