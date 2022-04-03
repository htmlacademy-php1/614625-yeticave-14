<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormatNumberTest extends TestCase
{
    public function testFormatNumber()
    {
        $result = formatNumber(999);
        $this->assertEquals( '999 ₽', $result);

        $result = formatNumber(10999);
        $this->assertEquals( '10 999 ₽', $result);

    }
}
