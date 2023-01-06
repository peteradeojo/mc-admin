<?php

namespace Database;

abstract class Client
{
  protected Database $client;

  protected function hookClient()
  {
    $this->client = new Database();
    $this->client->connect();
  }

  public static function getClient()
  {
    return new Database();
  }
}
