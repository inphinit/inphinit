<?php
namespace Controllers;

use Inphinit\App;
use Inphinit\Diagnostics\Checkup;
use Inphinit\Experimental\Utility\Markdown;
use Inphinit\Viewing\View;

class CheckupController
{
    public function checkup()
    {
        $check = new Checkup();

        $errors = $check->getErrors();
        $warnings = $check->getWarnings();

        View::data('environment', App::config('environment'));

        $parser = new Markdown();

        foreach ($errors as &$error) {
            $error = $parser->fromInlineString($error);
        }

        foreach ($warnings as &$warning) {
            $warning = $parser->fromInlineString($warning);
        }

        View::render('checkup', [
            'errors' => $errors,
            'warnings' => $warnings,
        ], View::UNSAFE);
    }
}
