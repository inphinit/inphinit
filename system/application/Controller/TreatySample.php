<?php
namespace Controller;

use Inphinit\Routing\Treaty;

class TreatySample extends Treaty
{
    public function __construct()
    {
        $this->format = Treaty::SLASH|Treaty::NOSLASH;
    }

    public function anyIndex()
    {
        return 'Hello, World! (from Controller)';
    }

    public function getAbout()
    {
        return 'About! (from Controller)';
    }
}
