<?php

require '../init.php';

require '../header.php';
?>
<div class="container">
  <h1>Tests</h1>
  <ul class="list-group" id="tests-list">
  </ul>
</div>

<div id="tests-modal" class="modal">
  <div class="modal-content p-1">
    <div class="modal-header">
      <h1>Test</h1>
    </div>
    <div class="modal-body py-2">
      <p>Loading</p>
    </div>
  </div>
</div>
<?php
$scripts = ['/lab/js/tests.js'];
require '../footer.php';
