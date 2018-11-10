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
        if (Input::hasPost('users'))
        {
            $this->usuario = (new Users)->getUser($id);
            if ($this->usuario->update(Input::post('users')))
            {
                MyFlash::show('success', 'Usuario actualizado con éxito', TRUE);
                return Redirect::toAction('index');
            }
            MyFlash::show('danger', 'Falló Operación', TRUE);
            $this->usuario = Input::post('users');
            return false;
        }
    }

    public function edit_photo()
    {
        if (Input::hasPost('oculto'))
        {
            $this->usuario = (new Users)->getUser(Input::post('oculto'));
            if ($this->usuario->saveWithPhoto())
            {
                MyFlash::show('success', 'Foto modificada con éxito', TRUE);
                return Redirect::toAction('index');
            }
        }
        MyFlash::show('danger', 'Falló Operación', TRUE);
        return false;
    }

}
