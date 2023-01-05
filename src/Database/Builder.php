<?php

namespace Database;

class Builder
{
  private $query;
  public function __construct()
  {
    $this->query = new Query();
  }

  public function integer($name, $required = true)
  {
    $this->query->appendLine("{$name} INT" . ($required ? ' NOT NULL' : ''));
    return $this;
  }

  public function decimal($name, $length = 8, $places = 2, $required = true)
  {
    $this->query->appendLine("{$name} DECIMAL($length, $places)" . ($required ? ' NOT NULL' : ''));
    return $this;
  }

  public function string($name, $length = 255, $required = true)
  {
    $this->query->appendLine("{$name} VARCHAR($length)" . ($required ? ' NOT NULL' : ''));
    return $this;
  }

  public function id()
  {
    $this->query->appendLine('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
    return $this;
  }

  public function sql()
  {
    return $this->query->sql();
  }

  public function foreign($column, $table, $onUpdate = 'CASCADE', $onDelete = 'CASCADE')
  {
    $this->query->appendLine("FOREIGN KEY ($column) REFERENCES $table(id) ON UPDATE $onUpdate ON DELETE $onDelete");
    return $this;
  }

  public function boolean($name, $required = true)
  {
    $this->query->appendLine("{$name} BOOLEAN" . ($required ? ' NOT NULL' : ''));
    $this->query->lastLineType = 'boolean';
    return $this;
  }

  public function timestamps()
  {
    $this->query->appendLine('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->query->appendLine('updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    return $this;
  }

  public function default($value)
  {
    switch ($this->query->lastLineType) {
      case 'boolean':
        $value = boolval($value) ? 1 : 0;
        break;

      default:
        $value = "'$value'";
        break;
    }
    $this->query->editLastLine($this->query->lastLine() . " DEFAULT $value");
    return $this;
  }

  public function unique($column)
  {
    $this->query->appendLine("UNIQUE ($column)");
    return $this;
  }

  public function date($name, $required = true)
  {
    $this->query->appendLine("{$name} DATE" . ($required ? ' NOT NULL' : ''));
    return $this;
  }
}
