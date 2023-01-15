<?php

namespace Database;

use ArrayAccess;

class Collection implements ArrayAccess
{
  private array $collection;
  private array $updated;

  public function __construct(array $data)
  {
    $this->collection = $data;
    $this->updated = $data;
  }

  public function offsetExists(mixed $offset): bool
  {
    return array_search($offset, $this->updated) !== false;
  }

  public function offsetGet(mixed $offset): mixed
  {
    if ($this->offsetExists($offset)) {
      return $this->updated[$offset];
    }
  }

  public function offsetSet(mixed $offset, mixed $value): void
  {
    $this->updated[$offset] = $value;
  }

  public function offsetUnset(mixed $offset): void
  {
    if ($this->offsetExists($offset)) {
      unset($this->updated[$offset]);
    }
  }
}
