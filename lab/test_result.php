<?php

use Lab\LabTest;

require '../init.php';

$testid = sanitizeInput($_GET['id'] ?? null);
$test = new LabTest($testid);

$title = "Test";
require '../header.php';
?>
<div class="container">
  <h2>Test Results: <u><?= $test->data['name'] ?></u></h2>
</div>

<div class="container" id="test-results">
  <div class="row">
    <div class="col-md-6">
      <h3>Name: <?= $test->data['name'] ?></h3>
    </div>
    <div class="col-md-6">
      <h3>Date: <?= date('dS l, F, Y', strtotime($test->data['date'])) ?></h3>
    </div>

    <div class="col-md-12">
      <table class="mt-3 table centered duo">
        <thead>
          <tr>
            <th>Test</th>
            <th>Result</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($test->data['test_results'] as $test_result) : ?>
            <tr>
              <td><?= $test_result['name'] ?></td>
              <td><?= $test_result['result'] ?></td>
            </tr>
          <?php endforeach ?>

          <tr>
            <td>
              Test Completed
            </td>
            <td>
              <?= $test->data['completed'] ? 'Yes' : 'No' ?>
            </td>
          </tr>
          <tr>
            <td>
              Lab Scientist
            </td>
            <td>
              <?= $test->data['lab_scientist']['name'] ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>


    <div class="col-md-12">
      <p class="my-3">
        <b>Remarks:</b>
        <?= $test->data['remarks'] ?>
      </p>
    </div>

  </div>
</div>
<div class="container mt-5">
  <button class="btn btn-success" class="print-button" data-target="#test-results">Print</button>
</div>
<!-- <?= var_dump($test->data) ?> -->
<?php

require '../footer.php';
