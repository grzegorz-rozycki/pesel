<?php
namespace GrzegorzRozycki\Pesel\Test;

use GrzegorzRozycki\Pesel\Pesel;
use PHPUnit\Framework\TestCase;


class PeselTest extends TestCase
{
    public function testPesel()
    {
        // PESEL object, is format correct, is checksum valid, is date valid, gender
        $peselList = [
            [ new Pesel('-2222222222'), false, false, false, null ],
            [ new Pesel('22222222222'), true, true, true, Pesel::GENDER_FEMALE ],
            [ new Pesel('00000000000'), true, true, false, Pesel::GENDER_FEMALE ],
            [ new Pesel('0'), false, false, false, null ],
            [ new Pesel('asza8898aaa'), false, false, false, null ],
            [ new Pesel('999999999999999'), false, false, false, null ],
            [ new Pesel('73070807059'), true, true, true, Pesel::GENDER_MALE ],
            [ new Pesel('70091013427'), true, true, true, Pesel::GENDER_FEMALE ],
            [ new Pesel('17060804103'), true, true, true, Pesel::GENDER_FEMALE ],
        ];

        foreach ($peselList as $index => $item) {
            $this->validate($index + 1, ...$item);
        }
    }

    private function validate($testNo, Pesel $pesel, $isFormatValid, $isChecksumValid, $isDateValid, $gender)
    {
        $this->assertEquals($isFormatValid, $pesel->validateFormat(), "[{$testNo}] Is format valid?");
        $this->assertEquals($isChecksumValid, $pesel->validateChecksum(), "[{$testNo}] Is checksum valid?");
        $this->assertEquals($isDateValid, $pesel->validateDate(), "[{$testNo}] Is date valid?");
        $this->assertEquals($gender, $pesel->getGender(), "[{$testNo}] Is gender valid?");
    }
}
