<?php
namespace App\Imports;

use Illuminate\Support\Str;



class TxtUTF8 {

  const ACCENT_VOWELS = [
    'Á','É','I','Ó','U'
  ];
  const VOWELS = [
    'A','E','I','O','U'
  ];
  public static function upAndRemoveAccents(string $string): string {
    $sustitudeArray = array_combine(self::ACCENT_VOWELS, self::VOWELS);
    $retString = Str::upper(trim($string));
    return strtr($retString, $sustitudeArray);
  }
}