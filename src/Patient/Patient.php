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

	function __construct(string $hospital_number = null)
	{
		try {
			$this->connect();

			if ($hospital_number) {
				$data = $this->select('biodata', where: "hospital_number='$hospital_number'")[0];

				$this->hospital_number = $data['hospital_number'];
				$this->name = $data['name'];
				$this->gender = $data['gender'];
				$this->birthdate = $data['birthdate'];
			}
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
		try {
			$this->insert(['vitals' => $this->data['vitals']]);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function saveVitals()
	{
		// echo '<pre>';
		// var_dump($this->data);
		// echo '</pre>';
		// die();
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
}
