<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Bluefalcon26\YokogawaAssessment\IntegerToWords;

final class IntegerToWordsTest extends TestCase
{
    private $php_int_max_possible_values = [
        "seven", // 2^4 / 2 - 1
        "one hundred twenty-seven", // 2^8 / 2 - 1
        "thirty-two thousand seven hundred sixty-seven", // 2^16 / 2 - 1
        "two billion one hundred forty-seven million four hundred eighty-three thousand six hundred forty-seven", // 2^32 / 2 - 1
        "nine quintillion two hundred twenty-three quadrillion three hundred seventy-two trillion thirty-six billion eight hundred fifty-four million seven hundred seventy-five thousand eight hundred seven" // 2^64 / 2 - 1
    ];

    private $php_int_min_possible_values = [
        "negative eight", // 2^4 / 2
        "negative one hundred twenty-eight", // 2^8 / 2
        "negative thirty-two thousand seven hundred sixty-eight", // 2^16 / 2
        "negative two billion one hundred forty-seven million four hundred eighty-three thousand six hundred forty-eight", // 2^32 / 2
        "negative nine quintillion two hundred twenty-three quadrillion three hundred seventy-two trillion thirty-six billion eight hundred fifty-four million seven hundred seventy-five thousand eight hundred eight" // 2^64 / 2
    ];

    // These test cases are taken directly from the assesment requirements
    //
    #[Test]
    public function fiveCanBeTranslated(): void
    {
        $integer = 5;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "five");
    }

    #[Test]
    public function fifty_fiveCanBeTranslated(): void
    {
        $integer = 55;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "fifty-five");
    }

    #[Test]
    public function five_hundred_fifty_fiveCanBeTranslated(): void
    {
        $integer = 555;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "five hundred fifty-five");
    }

    #[Test]
    public function five_thousand_five_hundred_fifty_fiveCanBeTranslated(): void
    {
        $integer = 5555;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "five thousand five hundred fifty-five");
    }

    // These tests handle extrapolated functionality
    //
    #[Test]
    public function oneCanBeTranslated(): void
    {
        $integer = 1;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "one");
    }

    #[Test]
    public function twoCanBeTranslated(): void
    {
        $integer = 2;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "two");
    }

    #[Test]
    public function threeCanBeTranslated(): void
    {
        $integer = 3;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "three");
    }

    #[Test]
    public function fourCanBeTranslated(): void
    {
        $integer = 4;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "four");
    }

    #[Test]
    public function sixCanBeTranslated(): void
    {
        $integer = 6;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "six");
    }

    #[Test]
    public function sevenCanBeTranslated(): void
    {
        $integer = 7;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "seven");
    }

    #[Test]
    public function eightCanBeTranslated(): void
    {
        $integer = 8;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "eight");
    }

    #[Test]
    public function nineCanBeTranslated(): void
    {
        $integer = 9;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "nine");
    }

    // These tests handle edge cases forseen by the author
    //
    #[Test]
    public function decimalsCannotBeTranslated(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $float = 5.5;
        $string = IntegerToWords::toWords($float);
    }

    #[Test]
    public function zeroCanBeTranslated(): void
    {
        $integer = 0;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "zero");
    }

    #[Test]
    public function negativeIntegersCanBeTranslated(): void
    {
        $integer = -5555;
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "negative five thousand five hundred fifty-five");
    }

    #[Test]
    public function leadingZeroIsTranslatedAsNegative(): void
    {
        $integer = array_fill(0,8,9);
        array_unshift($integer, 0);
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "negative ninety-nine million nine hundred ninety-nine thousand nine hundred ninety-nine");
    }

    // PHP_INT_MAX is often 2,147,483,647 (32 bit) or 9,223,372,036,854,775,807 (64 bit), but for future-proofing, we will provide support for 2147483647 ^3, or 'nine octillion...'.

    #[Test]
    public function php_int_maxCanBeTranslated(): void
    {
        $integer = PHP_INT_MAX;
        $string = IntegerToWords::toWords($integer);

        $this->assertContains($string, $this->php_int_max_possible_values);
    }

    #[Test]
    public function php_int_minCanBeTranslated(): void
    {
        $integer = PHP_INT_MIN;
        $string = IntegerToWords::toWords($integer);

        $this->assertContains($string, $this->php_int_min_possible_values);
    }

    // note that 'max supported integer' is considered an arbitrary operational requirement, not a functional definition, so should not be defined as a CONSTANT in source code. 
    #[Test]
    public function maxSupportedIntegerCanBeTranslated(): void
    {
        $integer = array_fill(0, 30, 9);
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "nine hundred ninety-nine octillion nine hundred ninety-nine septillion nine hundred ninety-nine sextillion nine hundred ninety-nine quintillion nine hundred ninety-nine quadrillion nine hundred ninety-nine trillion nine hundred ninety-nine billion nine hundred ninety-nine million nine hundred ninety-nine thousand nine hundred ninety-nine");
    }

    #[Test]
    public function minSupportedIntegerCanBeTranslated(): void
    {
        $integer = array_fill(0, 30, 9);
        array_unshift($integer, "-");
        $string = IntegerToWords::toWords($integer);

        $this->assertSame($string, "negative nine hundred ninety-nine octillion nine hundred ninety-nine septillion nine hundred ninety-nine sextillion nine hundred ninety-nine quintillion nine hundred ninety-nine quadrillion nine hundred ninety-nine trillion nine hundred ninety-nine billion nine hundred ninety-nine million nine hundred ninety-nine thousand nine hundred ninety-nine");

    }

    #[Test]
    public function hugeIntegersCannotBeTranslated(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $huge = array_fill(0, 31, 1);
        $string = IntegerToWords::toWords($huge);
    }
}
?>
