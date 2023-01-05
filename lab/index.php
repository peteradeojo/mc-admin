<?php

require '../init.php';

$lab_tests = $db->join(
  ['visits as vis', 'lab_tests as lt'],
  [
    [
      'type' => 'left',
      'on' => 'vis.lab_tests_id = lt.id'
    ]
  ],
  where: "vis.lab_tests_id is not null AND (lt.results is null or lt.status = 0)"
);

$title = 'Laboratory';
require '../header.php';
?>
<div class="container">
  <h1>Laboratory</h1>

  <div class="row">
    <div class="col-md-4">
      <a href="/lab/pending_tests.php" class="action-card p-1">
        <i class="fa fa-syringe fa-2x"></i>
        <span class="label">Pending</span>
        <span class="count"><?= count($lab_tests) ?></span>
      </a>
    </div>
    <div class="col-md-4">
      <a href="/lab/tests.php" class="action-card p-1">
        <i class="fa fa-check fa-2x"></i>
        <span class="label">Test Results</span>
      </a>
    </div>
    <div class="col-md-4"></div>
  </div>
</div>
<?php
require '../footer.php';
