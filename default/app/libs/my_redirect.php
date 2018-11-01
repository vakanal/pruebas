<?php

class MyRedirect extends Redirect
{

    public static function to($route = '', $seconds = 0, $statusCode = 302)
    {
        $route || $route = Router::get('controller_path') . '/';

        $route = ltrim($route, '/');

        if ($seconds) {
            header("Refresh: $seconds; url=$route");
            return;
        }
        
        header('Location: '.$route, TRUE, $statusCode);
        $_SESSION['KUMBIA.CONTENT'] = ob_get_clean();
        View::select(null, null);
    }

}