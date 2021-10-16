<?php

use Auth\Auth;

require '../init.php';

$visit_id = $_GET['id'];

Auth::redirectOnFalse($visit_id, '/doc/reviews.php');

$visit = $db->join(
  ['visits as vis', 'biodata as bi'],
  [
    [
      'type' => 'left',
      'on' => 'vis.hospital_number = bi.hospital_number'
    ]
  ],
  where: "vis.id = '$visit_id'"
)[0];

$title = "Review for $visit[name]";
// print_r($visit);

require '../header.php';
?>
<div class="container">
  <h1><?= $visit['name'] ?></h1>

  <div class="row row-reverse">
    <div class="col-md-6"></div>
  </div>

  <div class="container">
    <?= print_r($visit, 1) ?>
  </div>
  <div class="row">
  </div>
</div>
<?php
require '../footer.php';
