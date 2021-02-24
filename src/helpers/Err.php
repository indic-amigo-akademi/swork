<?php
class Err
{
    // private $error, $message;
    static function view($error = 404, $message = 'Not Found')
    {
        header("HTTP/1.0 $error $message");
        if (file_exists(realpath(VIEW_PATH . "errors/{$error}.ptml"))) {
            return Template::view("errors/{$error}.ptml");
        } else {
            return Template::view("errors/default_error.ptml", [
                'error' => $error,
                'message' => $message,
            ]);
        }
    }
}
?>
