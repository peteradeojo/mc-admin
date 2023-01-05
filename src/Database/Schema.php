<?php

namespace Database;

class Schema extends Client
{
  public function createTable(string $table, array $columns)
  {
    $sql = "CREATE TABLE $table (";
    foreach ($columns as $column) {
      $sql .= $column . ', ';
    }
    $sql = rtrim($sql, ', ');
    $sql .= ')';

    return $sql;
  }
}
