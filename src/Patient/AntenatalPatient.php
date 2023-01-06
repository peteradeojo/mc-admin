<?php

namespace Patient;

use Database\Client;
use Database\Database;
use Exception;

class AntenatalPatient extends NewPatient
{
  private $client;
  private $data;
  private $original = [];

  public $hospital_number;

  function __construct($data = null)
  {
    $this->data = $data;

    $this->client = Client::getClient();
    $this->client->connect();

    $this->mapData($this->data);

    if (static::validateLMP($data['flmp'])) {
      $this->data['edd'] = static::getEdd($data['flmp']);
    }
  }

  public function save()
  {
    $this->original['hospital_number'] = Patient::calculateHospitalNumber($this->original['category']);
    $this->original['create_time'] = date('Y-m-d H:i:s');
    $this->original['update_time'] = date('Y-m-d H:i:s');

    $this->hospital_number = $this->original['hospital_number'];

    try {
      $this->client->beginTransaction();
      $this->client->insert(['biodata' => $this->original]);
      $id = $this->client->lastId();

      $this->original['id'] = $id;
      $this->client->commit();

      return $this->original;
    } catch (\Throwable $th) {
      $this->client->rollback();
      throw new Exception($th->getMessage());
    }
  }

  public function startAntenatalSession()
  {
    $antenatalSession = [
      'patient_id' => $this->original['id'],
      'flmp' => $this->data['flmp'],
      'edd' => $this->data['edd'],
      'delivery_status' => 0,
    ];

    $this->client->beginTransaction();

    $data = [
      'antenatal_sessions' => $antenatalSession,
    ];

    if ($this->data['hmo']) {
      $data['insurance_data'] = $this->getInsuranceData();
    }

    try {
      $this->client->insert($data, replaceInto: true);
      $id = $this->client->lastId();

      $this->client->commit();

      return $id;
    } catch (\Throwable $th) {
      $this->client->rollback();
      throw new Exception($th->getMessage());
    }

    return $antenatalSession;
  }

  public static function validateLMP($lmp)
  {
    $lmp = strtotime($lmp);
    if (!$lmp) {
      throw new Exception('Invalid LMP');
    }
    $today = strtotime(date('Y-m-d'));

    if ($lmp > $today) {
      throw new Exception('LMP cannot be greater than today');
    }

    return $lmp;
  }

  public static function getEdd($lmp)
  {
    $lmp = self::validateLMP($lmp);
    $edd = strtotime('+9 months +7 days', $lmp);
    return date('Y-m-d', $edd);
  }

  public static function getGestationalAge($lmp)
  {
    $lmp = self::validateLMP($lmp);
    $today = strtotime(date('Y-m-d'));
    $diff = $today - $lmp;
    $weeks = floor($diff / (7 * 24 * 60 * 60));
    $days = floor(($diff - $weeks * 7 * 24 * 60 * 60) / (24 * 60 * 60));
    return $weeks . ' weeks ' . $days . ' days';
  }
}
