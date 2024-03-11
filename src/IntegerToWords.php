<?php declare(strict_types=1);

namespace Bluefalcon26\YokogawaAssessment;

use InvalidArgumentException;

class IntegerToWords
{
    // The switch statement in this function makes it easy to extend this
    // to accept more parameter types in the future
    public static function toWords(mixed $integer): string
    {
        $type = gettype($integer);
        switch ($type)
        {
        case "integer":
            return self::integerToWords($integer);
        case "array":
            return self::arrayToWords($integer);
        default:
            throw new InvalidArgumentException("IntegerToWords::toWords only accepts integer or array parameters");
        }
    }

    protected static function integerToWords(int $integer): string
    {
        // handle the special case of zero
        if ($integer === 0)
        {
            return "zero";
        }
        else
        {
            return self::arrayToWords(array_map('intval', str_split((string)$integer)));
        }
    }

    // check first digit for possible negative indicator,
    // then read left-to-right (BigEnd first)
    protected static function arrayToWords(array $intArray): string
    {
        // handle the special case of zero
        if (count($intArray) == 1 && $intArray[0] === 0)
        {
            return "zero";
        }

        // index the array numerically in case the parameter is not well formed
        // don't interate over nonsensical or empty key/value pairs
        $intArray = array_values($intArray);

        $result = "";

        // we want to apply special logic to the first character in the array
        // in order to determine if the integer is negative.
        // Otherwise, we could just array_reverse the parameter and iterate
        // through it with array_walk
        if ($intArray[0] == '-' || $intArray[0] === 0)
        {
            array_shift($intArray);
            $result .= "negative ";
        }

        // this only works if the array is well-formed, eg by using array_values
        //
        // using something like 'while (!empty($intArray))... array_pop' saves memory
        // but requires more operations
        //
        // array_reverse flips the array so the array index of the digit is its numerical order
        $intArray = array_reverse($intArray);

        $zero_length = count($intArray) - 1;

        // use tenDash as a signal variable for teens digits
        $tenDash = false;

        for ($x = $zero_length; $x >= 0; $x--)
        {
            $digit = $intArray[$x];
            $digitName = self::digitToWords($digit, $x, $tenDash);
            if ($digitName == "ten-")
            {
                $tenDash = true;
            }
            else
            {
                $tenDash = false;
                $result .= $digitName;
            }
        }

        return $result;
    }

    // note: ordinal digits are zero_based (begining with "zeroth" not "first")
    protected static function digitToWords(int $cardinal, int $ordinal, bool $teens)
    {
        if (gettype($cardinal) != "integer")
        {
            throw new InvalidArgumentException("Invalid parameter type supplied for cardinal digit. Expected an integer.");
        }

        $relative_order = $ordinal % 3;

        switch ($relative_order)
        {
        case 0:
            $digitName = $teens ? self::nameDigitTeens($cardinal) : self::nameDigitOnes($cardinal);
            return $digitName . self::nameDigitOrderGroup($ordinal);
        case 1:
            return self::nameDigitTens($cardinal);
        case 2:
            $digitName = self::nameDigitOnes($cardinal);
            return ($digitName == "") ? "" : $digitName . " hundred ";
        default:
            throw new InvalidArgumentException("Invalid parameter supplied for ordinal digit");
        }
    }

    protected static function nameDigitOnes($cardinal_digit)
    {
        switch ($cardinal_digit)
        {
        case 0:
            return "";
        case 1:
            return "one";
        case 2:
            return "two";
        case 3:
            return "three";
        case 4:
            return "four";
        case 5:
            return "five";
        case 6:
            return "six";
        case 7:
            return "seven";
        case 8:
            return "eight";
        case 9:
            return "nine";
        default:
            throw new InvalidArgumentException("Invalid parameter supplied for cardinal digit");
        }
    }

    protected static function nameDigitTens($cardinal_digit)
    {
        switch ($cardinal_digit)
        {
        case 0:
            return "";
        case 1:
            // this acts as a signaling string
            return "ten-";
        case 2:
            return "twenty-";
        case 3:
            return "thirty-";
        case 4:
            return "forty-";
        case 5:
            return "fifty-";
        case 6:
            return "sixty-";
        case 7:
            return "seventy-";
        case 8:
            return "eighty-";
        case 9:
            return "ninety-";
        default:
            throw new InvalidArgumentException("Invalid parameter supplied for cardinal digit");
        }
    }

    protected static function nameDigitTeens($cardinal_digit)
    {
        switch ($cardinal_digit)
        {
        case 0:
            return "ten";
        case 1:
            return "eleven";
        case 2:
            return "twelve";
        case 3:
            return "thirteen";
        case 4:
            return "fourteen";
        case 5:
            return "fifteen";
        case 6:
            return "sixteen";
        case 7:
            return "seventeen";
        case 8:
            return "eighteen";
        case 9:
            return "nineteen";
        default:
            throw new InvalidArgumentException("Invalid parameter supplied for cardinal digit");
        }
    }

    protected static function nameDigitOrderGroup($ordinal_digit)
    {
        $order_group = $ordinal_digit / 3;
        switch ($order_group)
        {
        case 0:
            return "";
        case 1:
            return " thousand ";
        case 2:
            return " million ";
        case 3:
            return " billion ";
        case 4:
            return " trillion ";
        case 5:
            return " quadrillion ";
        case 6:
            return " quintillion ";
        case 7:
            return " sextillion ";
        case 8:
            return " septillion ";
        case 9:
            return " octillion ";
        default:
            throw new InvalidArgumentException("Invalid parameter supplied for ordinal digit:  $ordinal_digit (must be 0-29 inclusive)");
        }
    }
}

?>
