<?php

namespace Patient;

use Database\Client;
use Error;
use Exception;
use Database\Database;

class Patient extends Database
{
	protected $hospital_number;
	protected $name;
	protected $gender;
	protected $data;
	protected $vitals;

	function __construct(string $hospital_number = null)
	{
		parent::__construct();

		try {
			if ($hospital_number) {
				$this->connect();
				$data = $this->select('biodata', where: "hospital_number='$hospital_number'")[0];

				$this->hospital_number = $data['hospital_number'];
				$this->name = $data['name'];
				$this->gender = $data['gender'];
				$this->birthdate = $data['birthdate'];
			}
		} catch (Exception | Error $e) {
			throw new Exception($e->getMessage());
		}
	}

	function takeVitals(array $vitals)
	{
		$this->data['vitals'] = $vitals;
		$this->data['vitals']['hospital_number'] = $this->hospital_number;
		$this->data['vitals']['date_submitted'] = date('Y-m-d H:i:s');
		$this->data['waitlist'] = ['status' => 1, 'hospital_number' => $this->hospital_number];
	}

	function getInfo(): array
	{
		return [
			'name' => $this->name,
			'hospital_number' => $this->hospital_number,
			'gender' => $this->gender,
			'birthdate' => $this->birthdate,
			'vitals' => @$this->vitals
		];
	}

	function save()
	{
		try {
			$this->insert(['vitals' => $this->data['vitals']]);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function saveVitals()
	{
		try {
			$this->save(['vitals' => $this->data['vitals']]);
			$this->update(['waitlist' => $this->data['waitlist'] + ['update_time' => date('Y-m-d H:i:s')]], where: "hospital_number='$this->hospital_number'", replaceInto: false);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function loadVitals()
	{
		try {
			$data = $this->select('vitals', where: "hospital_number='$this->hospital_number' ORDER BY date_submitted DESC LIMIT 1")[0];
			$this->vitals = $data;

			$taken_by = $this->select('staff', where: "username='{$this->vitals['taken_by']}'")[0];
			$this->vitals['taken_by'] = $taken_by;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function getVitals()
	{
		return @$this->vitals;
	}

	protected function getData()
	{
		return $this->data;
	}

	public static function calculateHospitalNumber($category)
	{
		$number = strtolower($category) . '-';
		// $pattern = "/\d{4}-(0|1)\d/-\d{2}/";
		$lastDigits = date('my');
		$client = Client::getClient();
		$client->connect();
		$data = (int) $client->select('biodata', 'count(id) as num', where: "hospital_number LIKE '$number%' and hospital_number like '%$lastDigits' ORDER BY hospital_number DESC LIMIT 1")[0]['num'];

		return $number . str_pad($data + 1, 3, '0', STR_PAD_LEFT) . '-' . $lastDigits;
	}
}
