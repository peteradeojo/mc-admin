<?php

namespace Lab;

use Exception;
use Database\Database;

class Test extends Database
{
  private $testid;
  private $data;

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
        table_rows: "bio.name, vis.*, lt.*"
      )[0];

      $data['tests'] = self::parseTests($data['lab_tests'], $data['results']);
      $this->data = $data;
      return $data;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  static function parseTests($tests, $results = '')
  {
    $tests = explode(',', $tests);
    $tests = array_map(function ($test) {
      return trim($test);
    }, $tests);

    $results = explode(',', $results);
    $results = array_map(function ($result) {
      return trim($result);
    }, $results);

    $tests = array_map(function ($test, $index) use ($results) {
      return [
        'name' => $test,
        'result' => $results[$index] ?? null
      ];
    }, $tests, array_keys($tests));

    return $tests;
  }

  public function save()
  {
  }

  public function saveResults($results, $user)
  {
    $results = implode(',', $results);
    $this->insert([
      'lab_tests' => [
        'id' => $this->testid,
        'results' => $results,
        'date' => date('Y-m-d H:i:s'),
        'submitted_by' => $user,
      ]
    ], replaceInto: true);
  }

  public function markCompleted()
  {
    $this->update([
      'lab_tests' => [
        'status' => 1
      ]
    ], where: "id='$this->testid'");
  }

  public function isCompleted(): bool
  {
    return boolval(($this->data['status'] ?? 0) == 1);
  }
}
