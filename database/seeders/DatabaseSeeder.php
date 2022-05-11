<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Municipality;
use App\Models\SettlementType;
use App\Models\FederalEntity;
use App\Models\Settlement;
use App\Models\ZipCode;
use App\Models\SettlementZipcode;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // /home/jmontilla/src/personal/retoBackbone/storage/app/public/cp-descarga-utf8.csv
        $contents = Storage::disk('local')->get('public/cp-descarga-utf8.csv');
        $lines = explode("\n", $contents);
        array_shift($lines);
        unset($contents);

        $fieldsByZipCode = $this->processLines($lines);

        $counter = 0;
        foreach ($fieldsByZipCode as $fields) {
            //try {
                $toBeInsertedIntoDb = $this->processFields($fields);
                $settlementType = new SettlementType();
                $federalEntity = new FederalEntity();
                $settlement = new Settlement();
                $zipCode = new ZipCode();
                

                /**** MUNICIPALITY  *****/
                $municipality = Municipality::find(intval($toBeInsertedIntoDb['municipality']->id));

                if (empty($municipality)) {
                    $municipality = new Municipality();

                    $municipality->id = intval($toBeInsertedIntoDb['municipality']->id);
                    $municipality->name = $toBeInsertedIntoDb['municipality']->name;
                    $municipality->save();
                }
                /*** SETTLEMENTS  ***/
                $settlementsArrIds = [];
                foreach ($toBeInsertedIntoDb['settlements'] as $settlement) {
                    /** SETTLEMENT_TYPE **/
                    $settTypeTobeSearched = $settlement['settlement_type'];

                    $settlementType = SettlementType::where('name', $settTypeTobeSearched['name'])->first();

                    if (empty($settlementType)) {
                        $settlementType = new SettlementType();
                        $settlementType->name = $settTypeTobeSearched['name'];
                        $settlementType->save();
                    }
                    $id = $settlement['id'];
                    $settlementFound = Settlement::find($id);
                    if (empty($settlementFound)) {
                        $settlementFound = new Settlement();
                        $settlementFound->id = $id;
                        $settlementFound->name = $settlement['name'];
                        $settlementFound->zone_type = $settlement['zone_type'];
                        $settlementFound->settlement_type_id = $settlementType->id;
                        $settlementFound->save();
                    }
                    $settlementsArrIds[] = $settlementFound->id;
                }
                /*** Federal Entity ***/
                $federalEntityId = intval($toBeInsertedIntoDb['federal_entity']->id);
                $federalEntity = FederalEntity::find($federalEntityId);
                if (empty($federalEntity)) {
                    $federalEntity = new FederalEntity();
                    $federalEntity->id = $federalEntityId;
                    $federalEntity->name = $toBeInsertedIntoDb['federal_entity']->name;
                    $federalEntity->code = null;
                    $federalEntity->save();
                }
                /*** ZIPCODE */
                $zipCodeString = $toBeInsertedIntoDb['zip_code'];
                $zipcode = ZipCode::where('zip_code', $zipCodeString)->first();
                if (empty($zipcode)) {
                    $zipcode = new ZipCode();
                    $zipcode->zip_code = $zipCodeString;
                    $zipcode->locality = $toBeInsertedIntoDb['locality'];
                    $zipcode->federal_entity_id = $federalEntityId;
                    $zipcode->municipality_id = $municipality->id;
                    $zipcode->save();
                    $zipcode->settlements()->attach($settlementsArrIds);
                }
                $this->command->info('Zip Code inserted '.$zipCodeString.', count ='.$counter);

            /*} catch (\Exception $e) {
                $this->command->error('At counter = '.$counter.'  '.$e->getMessage());
                $this->command->info(\json_encode($toBeInsertedIntoDb));
                dd([
                    'zipCodeAsString'       => $zipCodeString,
                    'tobeInsertedIntoDb'    => $toBeInsertedIntoDb,
                ]);
            }*/
            $counter++;
        }
    }
    private function processLines(array $lines): array {
        $fixedArray = [];
        foreach ($lines as $line) {
            $workingLine = $line;
            $tempArray          = explode('|', $workingLine);
            $zipCodeAsIndex     = $tempArray[0];
            $fixedArray[$zipCodeAsIndex][] = $tempArray;
        }
        return $fixedArray;
    }
    private function processFields(array $fields): array {
        /** 
         * 0 -> zip code
         * 5 -> locality
         * federal_entity {
         *      7 -> id
         *      4 -> name
         *      6 -> code
         * }
         * settlements [
         *      10 -> id
         *      1 -> name
         *      12 -> zone_type
         *      settlement_type {
         *          2 -> name
         *      }
         *  municipality {
         *      9 -> id
         *      3 -> name
         *  }
         * ]
        */
        $return = [];
        if (empty($fields)) {
            return [];
        }
        foreach ($fields as $field) {
            if (count($field)<10) {
                $this->command->warn('Field with less than 10 values!');
                dump($field);
            }
            $federalEntity = new \StdClass();
            $municipality = new \StdClass();
            $federalEntity->id = $field[7];
            $federalEntity->name = Str::upper($field[4]);
            $federalEntity->code = null;

            $municipality->id = $field[9];
            $municipality->name = Str::upper($field[3]);
            
            if (Str::startsWith($field[0], '0') === false) {
                $return['zip_code'] = '0' . $field[0];
            } else {
                $return['zip_code'] = $field[0];
            }
            $return['locality'] = Str::upper($field[5]);
            $return['federal_entity'] = $federalEntity;
            $return['settlements'][] = [
                'id'        =>  $field[10] ?? NULL,
                'name'      =>  Str::upper($field[1]),
                'zone_type' =>  Str::upper($field[11]),
                'settlement_type' => [
                    'name'  => $field[2],
                ],
            ];
            $return['municipality'] =  $municipality;
        }
        return $return;

    }
}
