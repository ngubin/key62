<?php

namespace Key62;

use Key62\Exceptions\Key62SymbolsException;
use Key62\Exceptions\Key62LengthException;
use Key62\Exceptions\Key62ParametersException;
use Key62\Exceptions\Key62BadResultException;

class Key62
{
    /**
     * @var array Array of characters for keys
     */
    protected $symbols = [];

    /**
     * @var int Number of characters for keys
     */
    protected $symbolsCount = 0;

    /**
     * @var array
     */
    protected $digits = [];

    /**
     * @var int The minimum length of the key
     */
    protected $length = 1;

    /**
     * Key62 constructor.
     *
     * @param string|null $symbols
     * @param int|null $length
     * @throws Key62LengthException
     * @throws Key62SymbolsException
     */
    public function __construct($symbols = null, $length = null)
    {
        if ($length !== null) {
            if (is_int($length) && $length >= 1) {
                throw new Key62LengthException('An integer greater than 1 is allowed');
            } else {
                $this->length = $length;
            }
        }

        if ($symbols === null) {
            $this->symbols = array_merge(
                range('a', 'z'),
                range('A', 'Z'),
                range(0, 9)
            );
        } else {
            if (!is_string($symbols)) {
                throw new Key62SymbolsException('Only the string is allowed');
            }

            $symbols = str_replace(' ', '', $symbols);
            $this->symbols = array_unique(str_split($symbols));

            if (!$this->symbols) {
                throw new Key62SymbolsException('Character set required');
            }
        }

        $this->symbolsCount = count($this->symbols);

        for ($i = 0; $i < $this->symbolsCount; $i++) {
            $this->digits[$this->symbols[$i]] = $i;
        }
    }

    /**
     * @param int $number
     * @return string
     * @throws Key62ParametersException
     */
    public function encode($number)
    {
        if (!(is_int($number) && $number >= 0)) {
            throw new Key62ParametersException('In the number only an integer greater than or equal to zero is allowed');
        }

        if ($this->length > 1) {
            $number += pow($this->symbolsCount, $this->length - 1);
        }

        $key = '';

        do {
            $index = $number % $this->symbolsCount;
            $key = $this->symbols[$index] . $key;
            $number = floor($number / $this->symbolsCount);
        } while ($number != 0);

        return $key;
    }

    /**
     * @param string $key
     * @return false|int
     * @throws Key62ParametersException
     * @throws Key62BadResultException
     */
    public function decode($key)
    {
        if (!is_string($key)) {
            throw new Key62ParametersException('In the key only а string is allowed');
        }

        if (($key = trim($key)) == '') {
            throw new Key62ParametersException('Empty key is not allowed');
        }

        $number = 0;
        $keyLength = strlen($key);

        for ($i = 0; $i < $keyLength; $i++) {
            $symbol = $key[$keyLength - $i - 1];

            if (isset($this->digits[$symbol])) {
                $number += $this->digits[$symbol] * pow($this->symbolsCount, $i);
            } else {
                return false;
            }
        }

        if ($this->length > 1) {
            $number -= pow($this->symbolsCount, $this->length - 1);
        }

        if ($number < 0) {
            throw new Key62BadResultException('It\'s possible that the wrong key length was passed');
        }

        return $number;
    }
}