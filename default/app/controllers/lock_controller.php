<?php

class LockController extends AppController 
{
    
    protected function before_filter() 
    {
        # Change template by default:
        View::template('adminlte/lockscreen');
    }

        public function index()
    {

    }

}
