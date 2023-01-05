<?php

namespace Database;

use Logger\Logger;

class Migration extends Client
{
  private static function execute($sql)
  {
    // echo $sql . "<br><br>";
    // return;
    $client = self::getClient();
    $client->connect();
    try {
      $client->getClient()->query($sql);
    } catch (\Throwable $th) {
      echo $th->getMessage();
      Logger::message("Error executing query. " . $th->getMessage(), 'error');
    } finally {
      // $client->disconnect();
    }
  }

  public static function createTableIfNotExists($tableName, $closure)
  {
    $builder = new Builder();
    $closure($builder);

    $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (" . $builder->sql() . ");";
    self::execute($sql);
  }

  public static function createTable($tableName, $closure)
  {
    $builder = new Builder();
    $closure($builder);

    $sql = "CREATE TABLE {$tableName} (" . $builder->sql() . ");";
    self::execute($sql);
  }

  public static function dropTable($tableName)
  {
    $sql = "DROP TABLE IF EXISTS {$tableName};";
    self::execute($sql);
  }
}
