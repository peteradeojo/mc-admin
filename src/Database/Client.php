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

  protected static function getClient()
  {
    return new Database();
  }
}
