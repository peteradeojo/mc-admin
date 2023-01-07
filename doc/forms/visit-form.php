<div class="form-group col-12">
  <label for="hospital_number">Card Number</label>
  <input type="text" name="hospital_number" readonly="readonly" class="form-control col-md-4" value="<?= $patient->getInfo()['hospital_number'] ?>">
</div>

<!-- Complaints -->
<div class="form-group col-md-6">
  <label for="complaint">Complaints</label>
  <div id="complaints-container" class="py-1">
    <?php
    $complaints = $visit['complaints'] ? explode(',', $visit['complaints']) : [];
    foreach ($complaints as $complaint) {
      echo <<<_
        <div class='d-flex justify-content-space-between'>
          <p>{$complaint}</p>
        </div>
        _;
    }
    ?>
  </div>
  <button type="button" class="btn" id="add-complaint" data-action="addInput" data-target="#complaints-container" data-inputname="complaints[]" data-inputtype="text" data-datalist="complaints-list">Add Complaint</button>
  <datalist id="complaints-list">
    <!-- <option value="Fever"></option> -->
    <?php
    $options = $db->select('available_complaints');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[complaint]'>";
    }
    ?>
  </datalist>
</div>

<!-- Assessments -->
<div class="form-group col-md-6">
  <label for="diagnosis" class="d-flex justify-content-space-between">
    <span>Assessments</span>
  </label>
  <div id="assessments" class="py-1">
    <?php
    $complaints = $visit['assessments'] ? explode(',', $visit['assessments']) : [];
    foreach ($complaints as $complaint) {
      echo <<<_
        <div class='d-flex justify-content-space-between'>
          <p>{$complaint}</p>
        </div>
      _;
    }
    ?>
  </div>
  <button type="button" data-action="addInput" data-target="#assessments" data-inputname="assessments[]" data-datalist="assessments-list" class="btn">Add Assessment</button>
  <datalist id="assessments-list">
    <?php
    $options = $db->select('assessments');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[assessment]'>";
    }
    ?>
  </datalist>
</div>

<!-- Investigations -->
<div class="form-group col-md-6">
  <label for="investigations">Investigations </label>

  <div id="investigations" class="py-1">
    <?php
    $complaints = $visit['investigations'] ? explode(',', $visit['investigations']) : [];
    foreach ($complaints as $complaint) {
      echo <<<_
        <div class='d-flex justify-content-space-between'>
          <p>{$complaint}</p>
        </div>
        _;
    }
    ?>
  </div>
  <datalist id="investigations-list">
    <?php
    $options = $db->select('available_investigations');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[investigation]'>";
    }
    ?>
  </datalist>

  <button class="btn" type="button" data-action="addInput" data-target="#investigations" data-inputname="investigations[]" data-datalist="investigations-list">Add Investigation</button>

</div>

<!-- Lab Tests -->
<div class="form-group col-md-6">
  <label>Lab Tests</label>
  <div id="lab-tests" class="py-1"></div>
  <button class="btn" type="button" data-inputname="lab_tests[]" data-target="#lab-tests" data-action="addInput" data-datalist="lab-tests-list">Add Test</button>
  <datalist id="lab-tests-list">
    <?php
    $options = $db->select('available_laboratory_tests');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[test]'>";
    }
    ?>
  </datalist>
</div>

<!-- Diagnoses -->
<div class="form-group col-md-6">
  <label for="diagnoses">Diagnoses</label>
  <div id="diagnoses" class="py-1"></div>
  <button type="button" class="btn" data-action="addInput" data-datalist="diagnoses-list" data-target="#diagnoses" data-inputname="diagnosis[]">Add Diagnosis</button>
  <datalist id="diagnoses-list">
    <?php
    $options = $db->select('available_diagnoses');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[diagnosis]'>";
    }
    ?>
  </datalist>
</div>

<!-- PRescriptions -->
<div class="form-group col-12">
  <label for="prescription">Prescription</label>
  <div id="prescriptions" class="py-1"></div>
  <datalist id="prescriptions-list">
    <?php
    $options = $db->select('available_prescriptions');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[prescription]'>";
    }
    ?>
  </datalist>
  <datalist id="quantity-list">
    <?php
    $options = $db->select('available_prescription_quantities');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[quantity]'>";
    }
    ?>
  </datalist>
  <datalist id="mode-list">
    <?php
    $options = $db->select('available_prescription_modes');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[mode]'>";
    }
    ?>
  </datalist>
  <datalist id="duration-list">
    <?php
    $options = $db->select('available_prescription_durations');
    $options = @$options[0] ? $options : [$options];
    foreach ($options as $option) {
      # code...
      echo "<option value='$option[duration]'>";
    }
    ?>
  </datalist>
  <button type="button" data-action="addPrescription" data-inputtype="text" data-datalist="prescriptions-list" data-inputname="prescriptions[]" data-target="#prescriptions" class="btn" onclick="addPrescription(this)">Add Prescription</button>
</div>
<div class="form-group col-md-6">
  <label for="admit">
    <input type="checkbox" name="admitted" id="admit" class="conditional-input" data-area='#admission-form'> Admit
  </label>
</div>
<div class="form-group col-md-6">
  <label for="review">
    <input type="checkbox" name="review" id="review" class="conditional-input" data-area="#review-form"> Set for Review
  </label>
</div>

<div id="admission-form" class="col-12 row" style="display: none">
  <h3>Admission Plans</h3>
  <div class="form-group col-md-6" id="admission-fluids-container">
    <label>Fluids</label>
    <div id="admission-fluids" class="py-1"></div>
    <button type="button" data-action="addFluid" data-target="#admission-fluids" data-inputtype="text" data-inputname="admission-instruction[]" data-datalist="fluids-list" class="btn" onclick="addAdmissionInstruction(this)">Add Fluid</button>
    <datalist id="fluids-list">
      <?php
      $options = $db->select('available_prescriptions', where: "type='0'");
      if (count($options)) {
        $options = @$options[0] ? $options : [$options];
        foreach ($options as $option) {
          echo "<option value='$option[prescription]'>$option[prescription]</option>";
        }
      }
      ?>
    </datalist>
  </div>
  <div class="form-group col-md-6" id="admission-drugs-container">
    <label>Drugs</label>
    <div id="admission-drugs" class="py-1"></div>
    <button type="button" data-action="addDrug" data-target="#admission-drugs" data-inputtype="text" data-inputname="admission-drugs[]" data-datalist="prescriptions-list" class="btn" onclick="addAdmissionInstruction(this)">Add Drug</button>
  </div>
</div>
<div id="review-form" style="display:none" class="col-12">
  <div class="form-group col-md-6">
    <label for="reviewdate">Review Date</label>
    <input type="date" name="review_date" id="reviewdate" class="form-control">
  </div>
  <div class="form-group col-md-6">
    <label for="review-notes">Review Notes</label>
    <textarea name="review_notes" id="review-notes" class="form-control"></textarea>
  </div>
</div>
<div class="form-group col-12">
  <label for="notes">Notes / Summary</label>
  <textarea name="notes" id="notes" cols="" rows="" class="form-control"></textarea>
</div>
<div class="form-group">
  <button type="submit" class="btn">Submit</button>
</div>