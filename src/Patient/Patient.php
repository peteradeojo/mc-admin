<?php

namespace Patient;

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

	function __construct(string $hospital_number)
	{
		try {
			$this->connect();
			$data = $this->select('biodata', where: "hospital_number='$hospital_number'");

			$this->hospital_number = $data['hospital_number'];
			$this->name = $data['name'];
			$this->gender = $data['gender'];
			$this->birthdate = $data['birthdate'];
		} catch (Exception | Error $e) {
			// echo $e->getMessage();
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
		// print_r($this->data);
		try {
			$this->insert($this->data);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function saveVitals()
	{
		try {
			$this->save(['vitals' => $this->data['vitals']]);
			$this->update(['waitlist' => $this->data['waitlist']], where: "hospital_number='$this->hospital_number'");
			// print_r(['vitals' => $this->data['vitals']]);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function loadVitals()
	{
		try {
			$data = $this->select('vitals', where: "hospital_number='$this->hospital_number' ORDER BY date_submitted DESC LIMIT 1");
			$this->vitals = $data;
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
}
