<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Imports\TxtArrayIterator;
use App\Imports\TxtImportInterpreter;
use App\Imports\TxtUTF8;

use App\Models\FederalUnit;
use App\Models\District;
use App\Models\Settlement;
use App\Models\SettlementType;
use App\Models\SettlementZipcode;
use App\Models\ZipCode;
class DatabaseSeeder extends Seeder
{
    const MAX_ZIPCODE_LENGTH = 5; /* ex: 99998 */
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $arrayOfStrings = new TxtArrayIterator('cp-descarga-utf8.txt');
        //$arrayOfStrings = new TxtArrayIterator('test-2.txt');
        $txtParser = new TxtImportInterpreter();
        $count = 0;
        $this->command->comment('Total lines imported '.$arrayOfStrings->count());
        while ($arrayOfStrings->valid()) {
            ++$count;
            $workingZipCode = $arrayOfStrings->key();
            $value = $arrayOfStrings->current();
            $this->command->getOutput()->write(
                '<info>Working with zipcode "'.$workingZipCode.'" and count = '.$count."</info>\e[1A\n"
            );
            $resArr = $txtParser->convertArrayRowsToElementsByZipCode(
                $value
            );
            /** SettlementsAndSettlementTypes **/
            $settlements    = $this->insertSettlementsAndSettlementTypes($resArr['settlements'], $workingZipCode);
            $federalUnit    = $this->insertFederalUnit($resArr['federal_units']);
            $district       = $this->insertDistrict($resArr['district'], $federalUnit);
            $zc             = $this->insertZipCodes($resArr, $settlements, $federalUnit, $district);
            $arrayOfStrings->next();
        }
        $this->command->info("\n".'Count = '.$count);
    }
    protected function insertSettlementsAndSettlementTypes(array $arr, string $zipCode): array {
        $settlementsModelArray = [];

        foreach ($arr as $index => $row) {
            $settleType = $row['settlement_type']['name'];
            $settleTypeModel = SettlementType::where('name', $settleType)->first();
            if (is_null($settleTypeModel)) {
                
                $settleTypeModel        = new SettlementType();
                $settleTypeModel->name  = $settleType;
                $settleTypeModel->save();
            }
 
            $settlement = Settlement::where('zip_code_id',$zipCode)->where('id',$row['id'])->first();
            if (is_null($settlement)) {
                $settlement                     = new Settlement();
                $settlement->id                 = $row['id'];
                $settlement->name               = TxtUTF8::upAndRemoveAccents($row['name']);
                $settlement->zone_type          = TxtUTF8::upAndRemoveAccents($row['zone_type']);
                $settlement->settlement_type_id = $settleTypeModel->id;
                $settlement->zip_code_id        = Str::padLeft($zipCode, self::MAX_ZIPCODE_LENGTH, '0');
                $settlement->save();
            }
            $settlementsModelArray[] = $settlement;
        }
        return $settlementsModelArray;
    }
    protected function insertZipCodes(array $arr, array $settlements, FederalUnit $fud, District $d): ZipCode {
        $zipCode = Str::padLeft($arr['zip_code'], self::MAX_ZIPCODE_LENGTH, '0');
        $zc = ZipCode::findByCode($zipCode)->first();
        if (is_null($zc)) {
            $zc                     = new ZipCode();
            $zc->code               = $zipCode;
            $zc->locality           = TxtUTF8::upAndRemoveAccents($arr['locality']);
            $zc->federal_unit_id    = $fud->id;
            $zc->district_id        = $d->id;
            $zc->save();
            /*foreach ($settlements as $settlement) {
                $settlement->zip_code_id = $zc->code;
                $settlement->save();
            }*/      
        }
        return $zc;
    }
    protected function insertDistrict(array $arr, FederalUnit $fud): District {
        $d = District::find($arr['id']);
        if (is_null($d)) {
            $d          = new District();
            $d->id      = $arr['id'];
            $d->name    = TxtUTF8::upAndRemoveAccents($arr['name']);
            $d->federal_unit_id = $fud->id;
            $d->save();
            //$d->federalUnit()->save($fud);
        }
        return $d;
    }
    protected function insertFederalUnit(array $arr): FederalUnit {
        $fud = FederalUnit::find($arr['id']);
        if (is_null($fud)) {
            $fud = new FederalUnit();
            $fud->id    = $arr['id'];
            $fud->name  = TxtUTF8::upAndRemoveAccents($arr['name']);
            $fud->code  = null;
            $fud->save();
        }
        return $fud;
    }
}
