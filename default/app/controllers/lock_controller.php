<?php

class LockController extends AppController 
{
    protected function before_filter() 
    {
        View::template('adminlte/lockscreen');
    }

        public function index()
    {

    }

}
