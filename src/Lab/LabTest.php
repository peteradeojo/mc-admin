<?php

namespace Lab;

use Database\Builder;
use Database\Client;
use Database\Model;
use Database\Database;

class LabTest extends Model
{
  private Database $client;
  public array $data;

  protected $table = 'lab_tests';

  public function __construct($id)
  {
    $this->client = Client::getClient();
    $this->client->connect();

    $this->loadData(whereCondition: "lt.id = '$id'");
  }

  protected function loadData($whereCondition = "")
  {
    $data = $this->client->join([
      'lab_tests as lt',
      'visits as vis',
      'biodata as bio'
    ], [
      [
        'type' => 'inner',
        'on' => 'vis.lab_tests_id = lt.id'
      ],
      [
        'type' => 'inner',
        'on' => 'vis.hospital_number = bio.hospital_number'
      ]
    ], where: $whereCondition);

    $this->data = $data[0];
    $this->data['test_results'] = Test::parseTests($this->data['lab_tests'], $this->data['results']);

    $labSci = $this->client->select('staff', where: "username='{$this->data['submitted_by']}'", rows: "concat(firstname, ' ',lastname) as name, username")[0];

    $this->data['lab_scientist'] = ['name' => $labSci['name'], 'username' => $labSci['username']];
  }
}
