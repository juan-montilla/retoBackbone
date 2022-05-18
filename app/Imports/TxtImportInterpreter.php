<?php
namespace App\Imports;

use Illuminate\Support\Str;

class TxtImportInterpreter {

  const MAX_ZIPCODE_LENGTH = 5;

  /** convertArrayRowsToElementsByZipCode
   * @param array $rows (string rows associated with a zipcode)
   * @return array everything rearranged in self-explanatory keys
   * 
   * format:
   *     
   * 0 -> zip code
    * 5 -> locality
    * federal_entity [
    *      7 -> id
    *      4 -> name
    *      6 -> code
    * ]
    * settlements [
    *      10 -> id
    *      1 -> name
    *      12 -> zone_type
    *      settlement_type {
    *          2 -> name
    *      }
    *  municipality [
    *      9 -> id
    *      3 -> name
    *  ]
    * ]
   */
  public function convertArrayRowsToElementsByZipCode(array $rows): array {
    $res = [];
    foreach ($rows as $rowString) {
      $res['zip_code']  = Str::padLeft($rowString[0], self::MAX_ZIPCODE_LENGTH, '0');
      $res['locality']  = $rowString[5];
      /** federal_entities */
      $res['federal_units'] = [
        'id'    => $rowString[7],
        'name'  => $rowString[4],
        'code'  => $rowString[6],
      ];
      $res['settlements'][] = [
        'id'              => $rowString[10],
        'name'            => $rowString[1],
        'zone_type'       => $rowString[11],
        'settlement_type' => [
          'name'  => $rowString[2],
        ]
      ];
      $res['district'] = [
        'id'          => $rowString[9],
        'name'        => $rowString[3]
      ];
    }
    return $res;
  }
}