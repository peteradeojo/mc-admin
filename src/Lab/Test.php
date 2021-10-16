<?php

namespace Lab;

use Exception;
use Database\Database;

class Test extends Database
{
  private $testid;
  function __construct(string $id)
  {
    $this->testid = $id;
  }

  function load_test()
  {
    try {
      $data = $this->join(
        ['visits as vis', 'biodata as bio', 'lab_tests as lt'],
        [
          ['type' => 'left', 'on' => 'vis.hospital_number = bio.hospital_number'],
          ['type' => 'left', 'on' => 'vis.lab_tests_id = lt.id']
        ],
        where: "vis.lab_tests_id='$this->testid'",
        table_rows: "bio.name, vis.*"
      )[0];
      return $data;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
}
