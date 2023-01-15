<?php

namespace Patient;

use Database\Database;
use Exception;

class DoctorVisit extends Database
{
	private $hosp_number;
	private $complaints;
	private $assessments;
	private $investigations;
	private $investigations_id;
	private $lab_tests;
	private $lab_tests_id;
	private $prescriptions;
	private $diagnosis;
	private $prescription_modes;
	private $prescription_quantity;
	private $prescription_duration;
	private $prescription_data;
	private $review;
	private $review_date;
	private $review_id;
	private $review_status;
	private $notes;
	private $admitted;
	private $admission_id;
	private $user;

	function __construct(string $username, $data = null)
	{
		parent::__construct();
		if (!$username) {
			throw new Exception('A user is required to submit a report');
		}

		// If the class is initiated with data from a visit form
		if ($data) {
			$this->user = $username;
			$this->hosp_number = $data['hospital_number'];

			// Initiate all relevant arrays to empty arrays if null data
			$this->prescriptions = @$data['prescriptions'] ? $data['prescriptions'] : [];
			$this->prescription_modes = @$data['mode-prescriptions'] ? $data['mode-prescriptions'] : [];
			$this->prescription_quantity = @$data['quantity-prescriptions'] ? $data['quantity-prescriptions'] : [];
			$this->prescription_duration = @$data['duration-prescriptions'] ? $data['duration-prescriptions'] : [];
			$this->complaints = @$data['complaints'] ? $data['complaints'] : [];
			$this->investigations = @$data['investigations'] ? $data['investigations'] : [];
			$this->assessments = @$data['assessments'] ? $data['assessments'] : [];
			$this->assessments = array_filter($this->assessments, function ($value) {
				return $value !== '';
			});
			$this->diagnosis = @$data['diagnosis'] ? $data['diagnosis'] : [];
			$this->lab_tests = @$data['lab_tests'] ? $data['lab_tests'] : [];


			$this->notes = @$data['notes'];

			// Review information
			if (@$data['review']) {
				$this->review = $data['review'];
				$this->review_date = $data['review_date'];
				$this->review_id = sha1("lt-{$this->hosp_number}" . time() + 2000);
				$this->review_status = 0;
			} else {
				$this->review = '0';
				$this->review_date = '';
			}

			// Admission information
			if (@$data['admitted']) {
				$this->admitted = $data['admitted'];
				$this->admission_id = sha1("$username-adm-" . time());
			} else {
				$this->admitted = '0';
			}

			// Create unique invetiagtion and review ids
			if ($this->investigations) {
				$this->investigations_id = sha1("inv-$this->hosp_number" . time() + 2400);
			}
			if ($this->lab_tests) {
				$this->lab_tests_id = sha1("lab-$this->hosp_number" . time() + 2400);
			}

			$this->collatePrescriptions();
		} else {
			echo $username;
		}
	}

	// Collect prescription data if provided
	private function collatePrescriptions()
	{
		$data = [];
		for ($i = 0; $i < count($this->prescriptions); $i += 1) {
			$data[$this->prescriptions[$i]] = [
				'mode' => $this->prescription_modes[$i],
				'quantity' => $this->prescription_quantity[$i],
				'duration' => $this->prescription_duration[$i],
				'status' => 0,
			];
		}
		$this->prescription_data = json_encode($data);
	}

	// Enter a new visitation record into the database
	function save()
	{
		try {
			$this->insert([
				'visits' => [
					'hospital_number' => $this->hosp_number,
					'complaints' => join(',', $this->complaints),
					'assessments' => join(',', $this->assessments),
					// 'diagnosis' => join(',', $this->assessments),
					'investigations' => join(',', $this->investigations),
					'lab_tests' => join(',', $this->lab_tests),
					'lab_tests_id' => $this->lab_tests_id,
					'investigations_id' => $this->investigations_id,
					'prescriptions' => $this->prescription_data,
					'admitted' => $this->admitted,
					'admission_id' => $this->admission_id,
					'review' => $this->review,
					'review_id' => $this->review_id,
					'review_date' => $this->review_date,
					'review_status' => $this->review_status,
					'notes' => $this->notes,
					'attendedby' => $this->user,
				],
			]);

			foreach ($this->complaints as $data) {
				# code...
				$this->update([
					'available_complaints' => ['complaint' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->assessments as $data) {
				# code...
				$this->update([
					'assessments' => ['assessment' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->prescription_duration as $data) {
				# code...
				$this->update([
					'available_prescription_durations' => ['duration' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->prescription_modes as $data) {
				# code...
				$this->update([
					'available_prescription_modes' => ['mode' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->prescription_quantity as $data) {
				# code...
				$this->update([
					'available_prescription_quantities' => ['quantity' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->prescriptions as $data) {
				# code...
				$this->update([
					'available_prescriptions' => ['prescription' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->lab_tests as $data) {
				# code...
				$this->update([
					'available_laboratory_tests' => ['test' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->diagnosis as $data) {
				# code...
				$this->update([
					'available_diagnoses' => ['diagnosis' => $data],
				], where: '1', replaceInto: true);
			}
			foreach ($this->investigations as $data) {
				# code...
				$this->update([
					'available_investigations' => ['investigation' => $data],
				], where: '1', replaceInto: true);
			}

			$this->update(['waitlist' => [
				'status' => 2
			]], where: "hospital_number='$this->hosp_number'");

			if ($this->admitted) {
				return $this->admission_id;
			} else
				return null;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function loadVisit(int | string $card_number)
	{
		try {
			$data = $this->select('visits', where: "hospital_number='$card_number'");
			return $data;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
