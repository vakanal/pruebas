<?php

Load::model('adminlte/users');

class RegisterController extends AppController 
{
    
    public function index()
    {
        View::template('adminlte/register');
    }
    
}
