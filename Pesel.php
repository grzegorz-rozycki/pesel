<?php

/**
 * {@link https://pl.wikipedia.org/wiki/PESEL}
 */
class Pesel
{
    protected $value = null;

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

    public function validateFormat()
    {
        return ((bool) filter_var($this->value, FILTER_VALIDATE_REGEXP, [ 'options' => [ 'regexp' => '/^\d{11}$/' ] ]));
    }

    public function validateChecksum()
    {
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

        return (0 === $crc % 10);
    }

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
}
