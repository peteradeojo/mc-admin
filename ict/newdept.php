<?php

require '../init.php';

if (sha1($_POST['password']) !== $staff->getUserData()['password']) {
  flash(info: ['message' => 'Invalid password', 'type' => 'danger']);
  header("Location: /logout.php");
  exit();
}

if ($staff->getAccessLevel() < '7') {
  flash(['type' => 'danger', 'message' => "You're not authorized to perform this action"]);
  header("Location: /ict/departments.php");
  exit();
}

try {
  $check = $db->select('departments', where: "name='$_POST[name]' and short_code='$_POST[code]'");
  if ($check) {
    flash(['type' => 'danger', 'message' => 'This department name or code already exists. Are you allowed to perform this action?']);
    echo "Already exosts";
  } else {
    $db->insert([
      'departments' => [
        'short_code' => trim(htmlspecialchars(strip_tags(stripslashes($_POST['code'])))),
        'name' => trim(htmlspecialchars(strip_tags(stripslashes($_POST['name']))))
      ]
    ]);
  }
  header("Location: /ict/departments.php");
} catch (Exception $e) {
  echo $e->getMessage();
}
