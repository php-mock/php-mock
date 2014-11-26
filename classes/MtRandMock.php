<?php

namespace malkusch\phpmock;

/**
 * Mocks PHP's mt_rand() function.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license WTFPL
 * @see mt_rand()
 */
class MtRandMock extends AbstractMock
{
    
    /**
     * @var int The returned number for the mt_rand() mock.
     */
    private $number;
    
    protected function getFunctionName()
    {
        return "mt_rand";
    }
    
    /**
     * Set the predicted random number.
     * 
     * @param int $number The predicted random number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }
    
    public function mockFunction()
    {
        return $this->number;
    }
}
