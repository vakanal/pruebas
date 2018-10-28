<?php

class LoginController extends AppController {

    protected function before_filter() {
        # Change template by default:
        View::template('adminlte/login');
    }

    public function index() {
        
    }

    /**
     * Método para iniciar sesión
     */
    public function entrar() 
    {
        $usuario = new Users();
        
        if ($usuario->iniciarSesion())
        {
            Router::redirect('/');
        }
    }

    /**
     * Método para cerrar sesión
     */
    public function salir($opt = false) 
    {
        if ($opt == 'no-script') 
        {
            Flash::info('Active el uso de JavaScript en su navegador para poder continuar.');
        }
        
        $usuario = new Usuario();
        
        if ($usuario->cerrarSesion()) 
        {
            Flash::valid("La sesión ha sido cerrada correctamente.");
        }
        
        View::select('entrar');
    }

}
