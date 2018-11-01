<?php

Load::model('adminlte/users');

class ProfileController extends AppController
{

    public function index()
    {
        $this->usuario = (new Users)->getUser(Session::get('id'));
    }

    public function edit($id)
    {
        # $usuario = new Users();
        $this->users = (new Users)->getUser($id);
        if (Input::hasPost('users'))
        {
            if ($this->users->update(Input::post('users')))
            {
                MyFlash::show('success', 'Usuario actualizado con éxito', TRUE);
                return Redirect::toAction('index');
            }
            MyFlash::show('danger', 'Falló Operación', TRUE);
            $this->users = Input::post('users');
            return false;
        }
    }

}
