<?php
require '../init.php';

$title = 'New Patient';
require '../header.php';
?>
<div class="container">
  <h1>New Patient</h1>

  <div class="mt-4">
    <form action="" method="post" class="row">
      <div class="form-group col-sm-6">
        <label for="category" class="required">Category</label>
        <select name="category" id="category" class="form-control">
          <?php
          try {
            //code...
            $categories = $db->select('available_categories', 'name as category', distinct: true, where: 'name is not null');
            array_map(function ($cat) {
              echo "<option value='$cat[category]'>" . strtoupper($cat['category']) . "</option>";
            }, $categories);
          } catch (Exception $e) {
            $categories = [];
          }
          ?>
        </select>
      </div>
      <div class="form-group col-6">
        <label for="firstname" class="required">Firstname</label>
        <input type="text" id="firstname" name="firstname" required="required" class="form-control">
      </div>
      <div class="form-group col-6">
        <label for="lastname" class="required">Lastname</label>
        <input type="text" id="lastname" name="lastname" required="required" class="form-control">
      </div>
      <div class="form-group col-6">
        <label for="middlename">Middlename</label>
        <input type="text" id="middlename" name="middlename" class="form-control">
      </div>
      <div class="form-group col-6">
        <label for=gender class=required>Gender</label>
        <!-- <input type="text" id="firstname" name="firstname" required="required" class="form-control"> -->
        <select name="gender" id="gender" class="form-control" required>
          <option value=1>Male</option>
          <option value=0>Female</option>
        </select>
      </div>
      <div class="form-group col-6">
        <label for="firstname">Firstname</label>
        <input type="text" id="firstname" name="firstname" required="required" class="form-control">
      </div>
    </form>
  </div>
</div>
<?php
require '../footer.php';
