<?php

class MyHtml extends Html
{
    
    public static function link($action, $text, $attrs = '') 
    {
        if (is_array($attrs)) 
        {
            $attrs = Tag::getAttrs($attrs);
        }
        
        return '<a href="http://localhost/pruebas/' . $action . '" ' . $attrs . '>' . $text . '</a>';   
    }
    
}
