<?php

class RegisterController extends AppController 
{
    
    protected function before_filter() 
    {
        # Change template by default:
        View::template('adminlte/register');
    }
    
    public function index()
    {
        
    }
}
