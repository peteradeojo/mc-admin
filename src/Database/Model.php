<?php

namespace Database;

abstract class Model
{
  abstract protected function loadData();

  abstract static function where(): Self;
}
