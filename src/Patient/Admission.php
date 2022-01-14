<?php

namespace Patient;

use Exception;
use Database\Database;

class Admission extends Database
{
	private $hospital_number;
	private $instructions = null;
	private $drugs = null;
	private $admission_id;
	private $staff;

	function __construct(array $data, string $username, $admission_id)
	{
		$this->hospital_number = $data['hospital_number'];
		if (@$data['admission-instruction']) {

			$this->instructions = [
				'instruction' => $data['admission-instruction'],
				'quantity' => $data['quantity-admission-instruction'],
				'mode' => $data['mode-admission-instruction'],
				'duration' => $data['duration-admission-instruction'],
			];
		}
		if (@$data['admission-drugs']) {
			$this->drugs = [
				'drugs' => $data['admission-drugs'],
				'quantity' => $data['quantity-admission-drugs'],
				'mode' => $data['mode-admission-drugs'],
				'duration' => $data['duration-admission-drugs'],
			];
		}
		$this->staff = $username;
		$this->admission_id = $admission_id;
	}

	private function compile()
	{
		$instructions = [];
		$drugs = [];
		if (@$this->instructions) {

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
		}
		if (@$this->drugs) {
			for ($i = 0; $i < count($this->drugs['drugs']); $i += 1) {
				$drugs[] = [
					'drug' => $this->drugs['drugs'][$i],
					'quantity' => $this->drugs['quantity'][$i],
					'mode' => $this->drugs['mode'][$i],
					'duration' => $this->drugs['duration'][$i],
					'status' => 'valid',
					'added_by' => $this->staff,
				];
			}
		}

		return [$instructions, $drugs];
	}

	function save()
	{
		try {
			//code...
			[$instructions, $drugs] = $this->compile();
			if (!$instructions and !$drugs) throw new Exception('No fluids or drugs were submitted');

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
						'instruction' => json_encode(array_merge($instructions, $drugs)),
						'instruction_status' => 1,
						'date_added' => date('Y-m-d H:i:s')
					]
				]
			);


			// Insert/replace fluids into database
			for ($i = 0; $i < count($instructions); $i += 1) {
				if ($instructions[$i]['instruction']) {
					$this->update([
						'available_prescriptions' => [
							'prescription' => $instructions[$i]['instruction'],
							'type' => "0",
						]
					], where: '1', replaceInto: true);
					$this->update([
						'available_prescription_durations' => [
							'duration' => $instructions[$i]['duration']
						]
					], where: '1', replaceInto: true);
					$this->update([
						'available_prescription_modes' => [
							'mode' => $instructions[$i]['mode']
						]
					], where: '1', replaceInto: true);
					$this->update([
						'available_prescription_quantities' => [
							'quantity' => $instructions[$i]['quantity']
						]
					], where: '1', replaceInto: true);
				}
			}

			echo "<br/><br/>";

			// Insert/replace drugs into DB
			for ($i = 0; $i < count($drugs); $i += 1) {
				if ($drugs[$i]['drug']) {
					$this->update([
						'available_prescriptions' => [
							'prescription' => $drugs[$i]['drug']
						]
					], where: '1', replaceInto: true);
					$this->update([
						'available_prescription_durations' => [
							'duration' => $drugs[$i]['duration']
						]
					], where: '1', replaceInto: true);
					$this->update([
						'available_prescription_modes' => [
							'mode' => $drugs[$i]['mode']
						]
					], where: '1', replaceInto: true);
					$this->update([
						'available_prescription_quantities' => [
							'quantity' => $drugs[$i]['quantity']
						]
					], where: '1', replaceInto: true);
					// print_r($drugs[$i]);
				}
			}
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
