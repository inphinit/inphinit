<?php
namespace Controller;

class TreatySample extends \Inphinit\Routing\Treaty
{
    public function __construct()
    {
        $this->format = self::SLASH | self::NOSLASH;
    }

    public function getIndex()
    {
        return 'getIndex';
    }

    public function anyFooBarBaz()
    {
        return 'anyFooBarBaz';
    }
}
