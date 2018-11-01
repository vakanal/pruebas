<?php

Load::model('adminlte/users');

class LoginController extends AppController {

    public function index() 
    {
        View::template('adminlte/login');
    }

    /**
     * Método para iniciar sesión
     */
    public function entrar() 
    {   
        $usuario = new Users();
        
        if ($usuario->iniciarSesion())
        {
            Redirect::to('index/index/');
        } 
        else 
        {
            Redirect::toAction('index');
        }
    }

    /**
     * Método para cerrar sesión
     */
    public function salir() 
    {   
        $usuario = new Users();
        
        if ($usuario->cerrarSesion()) 
        {
            # Flash::valid("La sesión ha sido cerrada correctamente.");
            MyFlash::show('success', 'La sesión ha sido cerrada correctamente.', TRUE);
        }
        # View::select('entrar');
        Redirect::toAction('index');
    }

}
