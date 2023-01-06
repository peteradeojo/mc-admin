<?php

require '../init.php';

$title = 'New Patient';

$stylesheets = ['/assets/css/tabs.css'];

$patientCategories = [
  'Adult',
  'Pediatric',
  'Family',
  'Fertility',
];

require '../header.php';
?>

<div class="container">
  <h2>Register Patient</h2>

  <form action="/rec/api/register.php" method="POST" id="newPatientForm">
    <div class="row my-3">
      <div class="form-group col-md-4">
        <label for="category">Category</label>
        <select name="category" id="category" class="form-control">
          <?php foreach ($patientCategories as $category) : ?>
            <option value="<?= substr(strtolower($category), 0, 3) ?>"><?= $category ?></option>
          <?php endforeach; ?>
          <option value="anc" data-conditional-form="./forms/antenatal.php">Antenatal</option>
        </select>
      </div>
      <div class="form-group col-md-4">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" required />
      </div>
      <div class="form-group col-md-4">
        <label for="gender">Sex/Gender</label>
        <select name="gender" id="gender" class="form-control">
          <option value="female">Female</option>
          <option value="male">Male</option>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="dob">Date of Birth</label>
        <input type="date" name="birthdate" id="dob" class="form-control" />
      </div>
      <div class="form-group col-md-12">
        <label for="address">Address</label>
        <textarea name="address" id="address" class="form-control"></textarea>
      </div>
      <div class="form-group col-md-4">
        <label for="state_of_origin">State of Origin</label>
        <input type="text" name="state_of_origin" id="state_of_origin" class="form-control" />
      </div>
      <div class="form-group col-md-4">
        <!-- input for tribe -->
        <label for="tribe">Tribe/Ethnic Group</label>
        <input type="text" name="tribe" id="tribe" class="form-control" />
      </div>
      <!-- form group for marital status -->
      <div class="form-group col-md-4">
        <label for="marital_status">Marital Status</label>
        <select name="marital_status" id="marital_status" class="form-control">
          <option value="single">Single</option>
          <option value="married">Married</option>
          <option value="divorced">Divorced</option>
          <option value="widowed">Widowed</option>
        </select>
      </div>
      <!-- Occupation -->
      <div class="form-group col-md-4">
        <label for="occupation">Occupation</label>
        <input type="text" name="occupation" id="occupation" class="form-control" />
      </div>
      <!-- Religion -->
      <div class="form-group col-md-4">
        <label for="religion">Religion</label>
        <select name="religion" id="religion" class="form-control">
          <option value="christianity">Christian</option>
          <option value="islam">Islam</option>
          <option value="other">Other</option>
        </select>
      </div>
      <!-- Phone -->
      <div class="form-group col-md-4">
        <label for="phone">Phone No.</label>
        <input type="text" name="phone" id="phone" class="form-control" />
      </div>
      <!-- Email -->
      <div class="form-group col-md-4">
        <label for="email">Email</label>
        <input type="email" name="email_address" id="email" class="form-control" />
      </div>
    </div>

    <div class="row mb-3">
      <!-- next of kin -->
      <div class="form-group col-md-4">
        <label for="next_of_kin">Next of Kin</label>
        <input type="text" name="next_of_kin" id="next_of_kin" class="form-control" />
      </div>
      <!-- next of kin phone -->
      <div class="form-group col-md-4">
        <label for="next_of_kin_phone">Next of Kin Phone No.</label>
        <input type="text" name="next_of_kin_phone" id="next_of_kin_phone" class="form-control" />
      </div>
      <!-- next of kin relationship -->
      <div class="form-group col-md-4">
        <label for="next_of_kin_relationship">Next of Kin Relationship</label>
        <input type="text" name="next_of_kin_relationship" id="next_of_kin_relationship" class="form-control" />
      </div>
      <!-- next of kin address -->
      <div class="form-group col-md">
        <label for="next_of_kin_address">Next of Kin Address</label>
        <input type="text" name="next_of_kin_address" id="next_of_kin_address" class="form-control" />
      </div>
    </div>

    <div id="extra-info"></div>

    <div class="mb-2">
      <h3>Health Insurance Information</h3>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="company">Company/Organization</label>
          <input type="text" name="company" id="company" class="form-control" />
        </div>

        <div class="form-group col-md-4">
          <label for="hmo-name">HMO Name</label>
          <input type="text" name="hmo" id="hmo-name" class="form-control" />
        </div>

        <div class="form-group col-md-4">
          <label for="id_number">HMO ID. No.</label>
          <input type="text" name="id_number" id="hmo_id_number" class="form-control" />
        </div>
      </div>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-success">Submit</button>
    </div>
  </form>
</div>

<?php
$scripts = ['/rec/js/newpatient.js'];
require '../footer.php';
