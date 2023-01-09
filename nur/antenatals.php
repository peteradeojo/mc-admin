<?php

require '../init.php';

$title = 'Antenatal Visits';
require '../header.php';
?>

<div class="container">
  <h1>Antenatal Visits</h1>
</div>

<div class="container">
  <table class="table" id="antenatal-visits-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $visits = $db->join(['antenatal_visits as anc_v', 'antenatal_sessions as ancs', 'biodata as bio'], [
        [
          'type' => 'left',
          'on' => 'anc_v.session_id = ancs.id'
        ],
        [
          'type' => 'left',
          'on' => 'ancs.patient_id = bio.id'
        ]
      ], orderby: "anc_v.created_at ASC", table_rows: "anc_v.id, bio.name, anc_v.status", where: "ancs.delivery_status is null");

      array_map(function ($visit) {
        $visit['name'] = ucfirst($visit['name']);
        $visit['status'] = $visit['status'] == 0 ? 'Pending' : 'Completed';
        echo <<<_HTML
        <tr>
          <td>{$visit['name']} </td>
          <td>{$visit['status']}</td>
          <td>
            <a href="/nur/anc_profile.php" class="btn btn-success">View Antenatal Profile</a>
            <a href="/nur/anc_vitals.php?id={$visit['id']}" class="btn btn-success">Take Vitals</a>
          </td>
        </tr>
        _HTML;
      }, $visits);
      ?>
    </tbody>
  </table>

</div>

<?php
$scripts = ['js/waitlist.js'];
require '../footer.php';
