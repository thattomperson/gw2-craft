<?php

namespace App\Console\Support;

interface Syncable {

  public function getFillable();
  public function getTable();
  public function formatFromApi($response): array;
  public function isRelation($key);

}