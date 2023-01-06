<?php

namespace Patient;

use Database\Client;
use Database\Database;


class NewPatient
{
  private Database $client;
  private $original = [];
  private $data;

  function __construct($data = [])
  {
    $this->client = Client::getClient();
    $this->client->connect();

    $this->mapData($data);
    $this->data = $data;
  }

  protected function mapData($data)
  {
    $result = $this->client->getClient()->query('SHOW COLUMNS FROM biodata');
    $result = $result->fetch_all(MYSQLI_ASSOC);

    $result = array_map(function ($field) {
      return $field['Field'];
    }, $result);

    array_map(function ($field) use ($data) {
      $this->original[$field] = $data[$field] ?? null;
    }, $result);
  }

  function save()
  {
    $this->original['hospital_number'] = Patient::calculateHospitalNumber($this->original['category']);
    $data = [
      'biodata' => $this->original,
    ];

    if ($this->data['hmo']) {
      $data['insurance_data'] = $this->getInsuranceData();
    }

    try {
      $this->client->beginTransaction();
      $result = $this->client->insert($data, replaceInto: true);

      $this->client->commit();
      return $result;
    } catch (\Throwable $th) {
      $this->client->rollback();
      throw new \Exception($th->getMessage());
    }
  }

  protected function getInsuranceData()
  {
    return [
      'hospital_number' => $this->original['hospital_number'],
      'hmo_name' => $this->data['hmo'] ?? null,
      'hmo_id' => $this->data['id_number'] ?? null,
      'company' => $this->data['company'] ?? null,
      'nhis_id' => $this->data['insurance'] ?? null,
    ];
  }
}
