<?php
namespace Controllers\Samples;

class TreatyController extends \Inphinit\Routing\Treaty
{
    public function getIndex()
    {
        return 'getIndex';
    }

    public function anyFooBarBaz()
    {
        return 'anyFooBarBaz';
    }
}
