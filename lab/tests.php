<?php

require '../init.php';

require '../header.php';
?>

<div class="container">
  <h1>Tests</h1>

  <table id="tests-table">
    <thead>
      <tr>
        <th>Patient</th>
        <th>Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<?php
$scripts = ['/lab/js/tests.js'];
require '../footer.php';
