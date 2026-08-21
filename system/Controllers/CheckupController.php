<?php
namespace Controllers;

use Inphinit\App;
use Inphinit\Diagnostics\Checkup;
use Inphinit\Viewing\View;

class CheckupController
{
    public function checkup()
    {
        $check = new Checkup();

        $errors = $check->getErrors();
        $warnings = $check->getWarnings();

        View::data('environment', App::config('environment'));

        View::render('checkup', [
            'errors' => self::codeTags($errors),
            'warnings' => self::codeTags($warnings),
        ], View::UNSAFE);
    }

    private static function codeTag($message)
    {
        $message = htmlspecialchars($message);
        $message = preg_replace('#(^|[\s\\(\\)\\[\\]\\{\\}])`([^`]+?)`([,.?!\s\\(\\)\\[\\]\\{\\}]|$)#', '$1<code>$2</code>$3', $message);
        $message = preg_replace('#(^|[\s\\(\\)\\[\\]\\{\\}])\*([^*]+?)\*([,.?!\s\\(\\)\\[\\]\\{\\}]|$)#', '$1<em>$2</em>$3', $message);

        return $message;
    }

    private static function codeTags(array $messages)
    {
        foreach ($messages as &$message) {
            $message = self::codeTag($message);
        }

        return $messages;
    }
}
