<?php

class MyFlash
{

    public static function show($type, $text, $dismissible = FALSE)
    {
        if ($dismissible)
        {
            $btn_dismiss = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $btn_dis = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
            echo '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">' . $btn_dis . $text . '</div>' . PHP_EOL;
        }
        else
        {
            echo '<div class="alert alert-' . $type . '" role="alert">' . $text . '</div>' . PHP_EOL;
        }
    }

}
