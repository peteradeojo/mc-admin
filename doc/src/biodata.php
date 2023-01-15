<?php

use Carbon\Carbon;

// echo '<pre>';
// var_dump($patient->getData('marital_status'));
// echo '</pre>';
// die();
?>

<table class="table-for-info">
  <tr>
    <td><b>Name</b></td>
    <td><?= $patient->getInfo()['name'] ?></td>
  </tr>
  <tr>
    <td><b>Gender</b></td>
    <td><?= $patient->getInfo()['gender'] ?></td>
  </tr>
  <tr>
    <td><b>Age</b></td>
    <td>
      <?php
      $today = Carbon::now();
      echo $today->diffInYears($patient->getInfo()['birthdate']);
      ?>
    </td>
  </tr>
  <tr>
    <td><b>Religion</b></td>
    <td><?= $patient->getData('religion') ?></td>
  </tr>
  <tr>
    <td><b>Marital Status</b></td>
    <td><?= $patient->getData('marital_status') ?></td>
  </tr>
  <?php if ($patient->getInfo()['gender'] === 'F') : ?>
    <tr>
      <td><b>Menstrual Status</b></td>
      <td><?= $patient->getData('menstrual_status') ?></td>
    </tr>
  <?php endif; ?>
</table>