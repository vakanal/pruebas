<?php

Load::model('adminlte/users');

class RegisterController extends AppController 
{
    
    public function index()
    {
        View::template('adminlte/register');
    }
    
    public function create()
    {
        if (Input::hasPost('users'))
        {
            $usuario = new Users(Input::post('users'));
            if ($usuario->create())
            {
                Flash::valid('Operación exitosa');
                Input::delete(); # Optional
                sleep(2);
                return Redirect::to('login/index/');
            }
            else
            {
                Flash::error('Falló Operación');
                $this->data = Input::post('users');
                return false;
            }
        }
    }

}
