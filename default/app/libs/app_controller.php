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
        $entrar = $this->manageLogin();
        if ($entrar && $this->controller_name !== 'register')
        {
            $this->manageACL();
        }

        /*
        # Comprobación del logeo
        $this->manageLogin();
        # Comprobaciones del ACL propio
        $this->manageACL();
        */
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
        else
        {
            return true;
        }
    }

    final protected function manageACL()
    {
        //instanciamos a la clase MyAcl , y le indicamos el ini a utlizar
        $acl = new MyAcl('privilegios');  //si no se especifica el archivo a usar, por defecto busca en privilegios.ini
        
        $modulo = $this->module_name; //obtenermos el modulo actual
        $controlador = $this->controller_name; //obtenemos el controlador actual
        $accion = $this->action_name; //y obtenemos la accion actual
        
        $privilegio = Session::get('rol');
        //verificamos si tiene ó no privilegios
        if (!$acl->check($privilegio, $modulo, $controlador, $accion))
        { 
            // si no posee los privilegios necesarios le enviamos un mensaje indicandoselo
            MyFlash::show('danger', "No posees suficientes PRIVILEGIOS para acceder a: {$modulo}/{$controlador}/{$accion}", TRUE);
            
            //no dejamos que entre al contenido de la url si no tiene permisos
            # View::select(NULL, 'adminlte/no_permiso');
            return Redirect::to('index/index/');
        }
    }

}
