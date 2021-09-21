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

	function __destruct()
	{
		$this->cxn->close();
	}
}
