<?php

use Database\Builder;
use Database\Client;
use Database\Collection;
use Lab\Test;

class Visit
{
  private Collection $collection;

  public function __construct($data)
  {
    $this->collection = new Collection($data);
  }

  public function __get($name)
  {
    return $this->collection->offsetGet($name);
  }

  public function __set($name, $value)
  {
    return $this->collection->offsetSet($name, $value);
  }

  public static function get($visit_id)
  {
    $db = Client::getClient();
    $db->connect();

    $visits = $db->join([
      'visits as vis',
      'lab_tests as tests',
      'biodata as bio'
    ], [
      [
        'type' => 'left',
        'on' => 'bio.hospital_number = vis.hospital_number'
      ],
      [
        'type' => 'left',
        'on' => 'vis.lab_tests_id = tests.id'
      ]
    ], orderby: "vis.id desc", where: "bio.hospital_number = '$visit_id'");

    $visits = array_map(function ($visit) {
      $visit['test_results'] ??= [];

      if ($visit['lab_tests'])
        $visit['test_results'] = Test::parseTests($visit['lab_tests'], $visit['results']);

      $visit['prescriptions'] = json_decode($visit['prescriptions']);


      return $visit;
    }, $visits);

    return new Self($visits);
  }
}
