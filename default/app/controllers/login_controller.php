<?php

class LoginController extends AppController 
{

    protected function before_filter() 
    {
        # Change template by default:
        View::template('adminlte/login');
    }
    
    public function index()
    {
        
    }
    
}
