<?php

namespace NFSeTest\Formatter;

use \NFSe\Formatter\FormatterException;

/**
 *
 * @author Pedro Marcelo
 */
class DecimalFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatValue()
    {
        $value = 5241.738193;
        /* @var $formatterManager \NFSe\Service\FormatterManager */
        $formatterManager = \NFSeTest\Bootstrap::getServiceManager()->get('NFSe\Service\FormatterManager');
        $decimalFormatter = $formatterManager->get('NFSe\Formatter\Decimal');
        $decimalFormatter->setPattern('+9.99');
        $this->assertEquals(5241.73, $decimalFormatter->format($value));
        $decimalFormatter->setPattern('+9.999');
        $this->assertEquals(5241.738, $decimalFormatter->format($value));
        $decimalFormatter->setPattern('9.9999');
        $this->assertEquals(1.7381, $decimalFormatter->format($value));
        $decimalFormatter->setPattern('9999999999999.99');
        $this->assertEquals(5241.73, $decimalFormatter->format($value));
    }
    
    public function testInvalidPatterns()
    {
        $invalidCharacters = "qwertyuiopasdfghjklçzxcvbnm!@#$%¨&*()-_=+[{]}:;/?|";
        $max = strlen($invalidCharacters);
        /* @var $formatterManager \NFSe\Service\FormatterManager */
        $formatterManager = \NFSeTest\Bootstrap::getServiceManager()->get('NFSe\Service\FormatterManager');
        $decimalFormatter = $formatterManager->get('NFSe\Formatter\Decimal');
        for ($i = 0; $i < 5; $i++) {
            $index = mt_rand(0, $max - 1);
            $character = $invalidCharacters[$index];
            try {
                $decimalFormatter->setPattern($character);
            } catch (FormatterException $ex) {
                $this->assertEquals("Invalid pattern '$character'. Read documentation for more information.", $ex->getMessage());
            }
        }
    }
    
    public function testEmptyPattern()
    {
        try {
            /* @var $formatterManager \NFSe\Service\FormatterManager */
            $formatterManager = \NFSeTest\Bootstrap::getServiceManager()->get('NFSe\Service\FormatterManager');
            $decimalFormatter = $formatterManager->get('NFSe\Formatter\Decimal');
            $decimalFormatter->format(9.992739);
            $this->fail("Null pattern was used");
        } catch (FormatterException $ex) {
            $this->assertEquals("Null pattern", $ex->getMessage());
        }        
    }
}
