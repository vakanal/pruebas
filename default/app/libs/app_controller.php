<?php

Load::model('adminlte/users');

/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
class AppController extends Controller 
{

    final protected function initialize() 
    {
        # Array indicador de donde estoy (datos para Partials):
        $this->breadcrumbs = array('modulo' => $this->module_name, 'controlador' => $this->controller_name, 'accion' => $this->action_name, 'parametros' => $this->parameters);
        # Template por defecto (AdminLTE v2.4.5 - blankPage):
        View::template('adminlte/starter');
        # Comprobación del logeo
        $this->manageLogin();
    }

    final protected function finalize() 
    {
        
    }

    final protected function manageLogin() 
    {
        //Verifico que haya iniciado sesión
        if (!Users::isValid() && $this->controller_name !== 'register') 
        {
            //Verifico que no genere una redirección infinita
            if (($this->controller_name !== 'login') && ($this->action_name != 'entrar' && $this->action_name != 'salir')) 
            {
                # Flash::warning('No has iniciado sesión.');
                MyRedirect::to('login/');
                return false;
            }
        }
    }

}
