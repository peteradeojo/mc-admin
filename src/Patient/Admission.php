<?php

namespace Patient;

use Exception;
use Database\Database;

class Admission extends Database
{
	private $hospital_number;
	private $instructions = [];
	private $admission_id;
	private $staff;

	function __construct(array $data, string $username, $admission_id)
	{
		$this->hospital_number = $data['hospital_number'];
		$this->instructions = [
			'instruction' => $data['admission-instruction'],
			'quantity' => $data['quantity-admission-instruction'],
			'mode' => $data['mode-admission-instruction'],
			'duration' => $data['duration-admission-instruction'],
		];
		$this->staff = $username;
		$this->admission_id = $admission_id;
	}

	private function compile()
	{
		$instructions = [];
		for ($i = 0; $i < count($this->instructions['instruction']); $i += 1) {
			$instructions[] = [
				'instruction' => $this->instructions['instruction'][$i],
				'quantity' => $this->instructions['quantity'][$i],
				'mode' => $this->instructions['mode'][$i],
				'duration' => $this->instructions['duration'][$i],
				'status' => 'valid',
				'added_by' => $this->staff,
			];
		}

		return $instructions;
	}

	function save()
	{
		try {
			//code...
			$instructions = $this->compile();

			$this->insert(
				[
					'admissions' => [
						'hospital_number' => $this->hospital_number,
						'admission_id' => $this->admission_id,
						'wardid' => 'placeholder',
						'date_admitted' => date('y-m-d H:i:s'),
						'admitted_by' => $this->staff
					],
					'admission_instructions' => [
						'admission_id' => $this->admission_id,
						'instruction' => json_encode($instructions),
						'instruction_status' => 1,
						'date_added' => date('Y-m-d H:i:s')
					]
				]
			);

			for ($i = 0; $i < count($instructions); $i += 1) {
				if ($instructions[$i]['instruction'])
					$this->update([
						'available_prescriptions' => [
							'prescription' => $instructions[$i]['instruction']
						]
					], where: '1', replaceInto: true);
				if ($instructions[$i]['duration'])
					$this->update([
						'available_prescription_durations' => [
							'duration' => $instructions[$i]['duration']
						]
					], where: '1', replaceInto: true);
				if ($instructions[$i]['mode'])
					$this->update([
						'available_prescription_modes' => [
							'mode' => $instructions[$i]['mode']
						]
					], where: '1', replaceInto: true);
				if ($instructions[$i]['quantity'])
					$this->update([
						'available_prescription_quantities' => [
							'quantity' => $instructions[$i]['quantity']
						]
					], where: '1', replaceInto: true);
			}


			// print_r($instructions);
		} catch (Exception $e) {
			//throw $th;
			throw new Exception($e->getMessage());
		}
	}

	function getAdmissionId()
	{
		return $this->admission_id;
	}
}
