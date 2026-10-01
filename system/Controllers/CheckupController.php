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
        $markdown = new Markdown();

        $errors = $check->getErrors();
        $warnings = $check->getWarnings();

        View::data('environment', App::config('environment'));
        View::data('markdown', $markdown);

        try {
            $php_build_date = Checkup::getPhpBuildDate();
        } catch (\Exception $ex) {
            $php_build_date = 'Unknown';
        }

        View::data('php_build_date', $php_build_date);

        foreach ($errors as $error) {
            $error = $error;
        }

        foreach ($warnings as $warning) {
            $warning = $warning;
        }

        View::render('checkup', [
            'errors' => $errors,
            'warnings' => $warnings,
        ]);
    }
}
