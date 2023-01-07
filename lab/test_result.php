<?php

use Lab\LabTest;

require '../init.php';

$testid = sanitizeInput($_GET['id'] ?? null);
$test = new LabTest($testid);

if (!$test->data) {
  newFlash('danger', 'Test not found');
  redirect('/lab/tests.php');
}

$title = "Test Results for " . $test->data['name'];
require '../header.php';
?>
<h2 class="only-print">Maternal-Child Specialists' Clinics</h2>
<div class="container">
  <h2 class="no-print">Test Results: <u><?= $test->data['name'] ?></u></h2>
  <h3 class="only-print">Test Results:
    <u class="no-print"><?= $test->data['name'] ?></u>
    <span class="only-print" style="font-weight: 100;"><?= $test->data['id'] ?></span>
  </h3>
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
              <b><?= $test->data['status'] > 0 ? 'Yes' : "<a href='/lab/test.php?id={$test->data['lab_tests_id']}'>No</a>" ?></b>
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
  <button class="btn btn-success no-print print-button" data-target="#test-results">Print</button>
</div>
<!-- <?= var_dump($test->data) ?> -->
<?php
$scripts = ['/lab/js/test_result.js'];
require '../footer.php';
