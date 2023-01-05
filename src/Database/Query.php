<?php

namespace Database;

class Query
{
  private $lines = [];
  public $lastLineType = null;

  public function appendLine($line)
  {
    $this->lines[] = $line;
  }

  public function lastLine()
  {
    return $this->lines[count($this->lines) - 1];
  }

  public function editLastLine($line)
  {
    $this->lines[count($this->lines) - 1] = $line;
  }

  public function sql()
  {
    return join(',', $this->lines);
  }
}
