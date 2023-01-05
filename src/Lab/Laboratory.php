<?php

namespace Lab;

use Database\Database;
use Exception;

class Laboratory extends Database
{
  function load_pending_tests()
  {
    try {
      $data = $this->join(
        ['visits as vis', 'biodata as bio', 'lab_tests as lt'],
        [
          ['type' => 'left', 'on' => 'vis.hospital_number = bio.hospital_number'],
          ['type' => 'left', 'on' => 'vis.lab_tests_id = lt.id']
        ],
        where: "vis.lab_tests_id is NOT NULL AND (lt.results is null or lt.status = 0)",
        table_rows: "bio.name, vis.*"
      );
      return $data;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
}
