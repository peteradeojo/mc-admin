<?php

use Auth\Auth;
use Lab\Laboratory;
use Lab\Test;

require '../init.php';

Auth::redirectOnFalse($_GET['id'], '/lab');

$testid = $_GET['id'];

$test = new Test($testid);
$test->connect();

$test_data = $test->load_test();

$title = $test_data['name'];

require '../header.php';
?>
<div class="container">
  <h1><?= $test_data['name'] ?></h1>

  <div class="container">
    <h2>Card Number: <u><?= $test_data['hospital_number'] ?></u></h2>

    <section>
      <!-- <form action="./api/submit_test_results.php" method="post"> -->
      <form method="post" id="submitTestResultsForm">
        <?php
        foreach ($test_data['tests'] as $testData) {
          echo "
            <div class='form-group'>
              <label for='test-$testData[name]'>$testData[name]</label>
              <input type='text' name='tests[]' id='test-$testData[name]' class='form-control' value='$testData[result]'>
            </div>
          ";
        }
        ?>
        <div class="form-group my-1">
          <label for="markComplete">
            <input type="checkbox" name="completed" id="markComplete" <?= $test->isCompleted() ? 'checked' : '' ?>>
            Mark as Completed
          </label>
        </div>

        <div class="form-group mt-1">
          <input type="hidden" name="testid" value="<?= $testid ?>">
          <input type="hidden" name="user" value="<?= $staff->getUsername() ?>">
          <button type="submit" value="Submit" class="btn btn-primary" <?= $test->isCompleted() ? 'disabled' : '' ?>>Submit</button>
        </div>
      </form>
    </section>
    <!-- <?php echo '<pre>';
          var_dump($test_data);
          echo '</pre>'; ?> -->
  </div>
</div>
<?php

$scripts = ['./js/tests.js'];
require '../footer.php';
