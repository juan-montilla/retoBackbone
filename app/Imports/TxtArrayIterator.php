<?php

namespace App\Imports;

use Illuminate\Support\Facades\Storage;

class TxtArrayIterator extends \ArrayIterator {

  public function __construct(string $filename) {
      $content = Storage::disk('public')->get($filename);
      $contentAsArray = explode("\n", $content);
      array_shift($contentAsArray);
      $arrFileLines = $this->convertLinesToArray($contentAsArray);
      parent::__construct($arrFileLines);
  }
  public function convertLinesToArray(array $arrOfStrings): array {
    $fixedArray = [];
    foreach ($arrOfStrings as $string) {
        $tempArray          = explode('|', $string);
        $zipCodeAsIndex     = $tempArray[0];
        $fixedArray[$zipCodeAsIndex][] = $tempArray;
    }
    return $fixedArray;  
  }
}
