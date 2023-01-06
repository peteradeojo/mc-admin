<?php

use Database\Database;

require '../../init.php';

$docs = $db->select('staff', where: "department='doc'");
$docs = array_map(function ($doc) {
  $doc['name'] = $doc['firstname'] . ' ' . $doc['lastname'];
  return $doc;
}, $docs);
?>

<div id="generated-form-data" class="mb-3">
  <h2>Antenatal Records</h2>
  <div class="row mb-3">
    <div class="form-group col-md-4">
      <label for="fmlp">FLMP</label>
      <input type="date" id='flmp' name='flmp' class="form-control">
    </div>
    <!-- <div class="form-group col-md-4">
      <label for="edd">EDD</label>
      <input type="text" id='edd' name='edd' class="form-control">
    </div> -->
    <div class="form-group col-md-4">
      <label for="educational-status">Educational Status</label>
      <select name="educational_status" id="educational_status" class="form-control">
        <option value="tertiary">Tertiary</option>
        <option value="secondary">Secondary</option>
        <option value="primary">Primary</option>
        <option value="illiterate">Illiterate</option>
      </select>
    </div>
    <div class="form-group .col-md-4">
      <label for="consultant">Consultant</label>
      <select name="consultant" id="consultant" class="form-control">
        <?php foreach ($docs as $doc) : ?>
          <option value="<?= $doc['id'] ?>"><?= $doc['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>


  <fieldset class="p-2">
    <legend><b>Spouse Information</b></legend>
    <div class="row">
      <div class="form-group col-md-4">
        <label for="spouse_name">Name</label>
        <input type="text" id='spouse_name' name='spouse_name' class="form-control">
      </div>
      <div class="form-group col-md-4">
        <label for="spouse_phone">Phone</label>
        <input type="text" id='spouse_phone' name='spouse_phone' class="form-control">
      </div>
      <!-- spouse occupation -->
      <div class="form-group col-md-4">
        <label for="spouse_occupation">Occupation</label>
        <input type="text" id='spouse_occupation' name='spouse_occupation' class="form-control">
      </div>
      <!-- spouse educational level -->
      <div class="form-group col-md-4">
        <label for="spouse_educational_level">Educational Level</label>
        <select name="spouse_educational_level" id="spouse_educational_level" class="form-control">
          <option value="tertiary">Tertiary</option>
          <option value="secondary">Secondary</option>
          <option value="primary">Primary</option>
          <option value="illiterate">Illiterate</option>
        </select>
      </div>
    </div>
  </fieldset>
</div>