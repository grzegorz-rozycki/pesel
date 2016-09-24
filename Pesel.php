<?php

/**
 * Class responsible for PESEL validation.
 *
 * PESEL (Powszechny Elektroniczny System Ewidencji Ludności) is a Polish citizen identity code.
 * A PESEL code contains information about a persons birth date and gender.
 * The class allows for code format, checksum and date validation.
 *
 * {@link https://pl.wikipedia.org/wiki/PESEL}
 */
class Pesel
{
    const GENDER_FEMALE = 'female';
    const GENDER_MALE = 'male';

    /**
     * @var string
     */
    protected $value = null;

    /**
     * @var bool
     */
    private $formatValid = null;

    /**
     * @var bool
     */
    private $checksumValid = null;

    /**
     * @param string
     */
    public function __construct($value)
    {
        $this->value = (string) $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Validates the code format. Should be exactly 11 numeric characters.
     *
     * @return bool
     */
    public function validateFormat()
    {
        if (null === $this->formatValid) {
            $this-> formatValid = (bool) filter_var($this->value, FILTER_VALIDATE_REGEXP,
                [ 'options' => [ 'regexp' => '/^\d{11}$/' ] ]);
        }

        return $this->formatValid;
    }

    /**
     * Validates code checksum.
     *
     * @return bool
     */
    public function validateChecksum()
    {
        if (null === $this->checksumValid) {
            $crc  = 1 * $this->value[0];
            $crc += 3 * $this->value[1];
            $crc += 7 * $this->value[2];
            $crc += 9 * $this->value[3];
            $crc += 1 * $this->value[4];
            $crc += 3 * $this->value[5];
            $crc += 7 * $this->value[6];
            $crc += 9 * $this->value[7];
            $crc += 1 * $this->value[8];
            $crc += 3 * $this->value[9];
            $crc += 1 * $this->value[10];

            $this->checksumValid = (0 === $crc % 10);
        }

        return $this->checksumValid;
    }

    /**
     * Retrieves birth date from code. Returns a DateTime object if date is valid null else.
     *
     * @return \DateTime | null
     */
    public function getDate()
    {
        if (!$this->validateFormat()) {
            return null;
        }

        $mm = (int) substr($this->value, 2, 2);
        $day = (int) substr($this->value, 4, 2);
        $month = $mm % 20;
        $year = null;

        switch ($mm - $month) {
            case 0:
                $year = 1900 + ((int) substr($this->value, 0, 2));
                break;
            case 80:
                $year = 1800 + ((int) substr($this->value, 0, 2));
                break;
            case 20:
                $year = 2000 + ((int) substr($this->value, 0, 2));
                break;
            case 40:
                $year = 2100 + ((int) substr($this->value, 0, 2));
                break;
            case 60:
                $year = 2200 + ((int) substr($this->value, 0, 2));
                break;
        }

        return (null !== $year && checkdate($month, $day, $year))
            ? new \DateTime($year . '-' . $month . '-' . $day)
            : null;
    }

    /**
     *
     */
    public function getGender()
    {
        if (!$this->validateFormat()) {
            return null;
        }

        return (0 === ($this->value[9] % 2) ? self::GENDER_FEMALE : self::GENDER_MALE);
    }

    public function isFemale()
    {
        return ($this->getGender() === self::GENDER_FEMALE);
    }
    }

    public function isMale()
    {
        return ($this->getGender() === self::GENDER_MALE);
    }
}
