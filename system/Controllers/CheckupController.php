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

        try {
            $php_build_date = Checkup::getPhpBuildDate();
        } catch (\Exception $ex) {
            $php_build_date = 'Unknown';
        }

        View::data('php_build_date', $php_build_date);

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
