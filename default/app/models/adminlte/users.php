<?php

class Users extends ActiveRecord 
{

    protected function initialize()
    {
        $this->validates_email_in('mail');
    }

    protected function before_save()
    {
        $this->pass = hash('sha3-512', $this->pass);
    }

    /**
     * Método para demostrar el correcto funcionamiento
     * de la conexión con la BD (by Álvaro GR)
     * @return array
     */
    public function getUsers() 
    {
        return $this->find();
    }

    /**
     * Método para verificar si el usuario ha iniciado sesion
     * @return boolean
     */
    public static function isValid() 
    {
        $auth = Auth2::factory('model');
        return $auth->isValid();
    }

    /**
     * Método para iniciar sesión
     * @return boolean
     */
    public function iniciarSesion() 
    {
        //Verifico si tiene una sesión válida con una ip
        if (self::isValid()) 
        {
            return true;
        } 
        else 
        {
            //Verifico si se ha autentificado
            if (Input::hasPost('mail') && Input::hasPost('pass')) 
            {
                $auth = Auth2::factory('model');  //Creo el objeto de Auth2 con el uso de validacion mediante modelos
                $auth->setLogin('mail'); //Defino cual es el campo del nombre de usuario
                $auth->setPass('pass'); //Defino cual es el campo del nombre de la contraseñah
                $auth->setAlgos('sha3-512');
                $auth->setCheckSession(true); //Se utiliza para que no inicie sesión en otro navegador (no me funciona :S)
                $auth->setModel('users'); //Indico cual es el modelo respectivo para que consulte en la base de datos
                $auth->setFields(array('id', 'mail', 'pass', 'nick', 'rol', 'create_at')); //Estos campos se almacenan en sesión automáticamente
                
                if ($auth->identify() && $auth->isValid()) //Verifico si el usuario es válido
                { 
                    sleep(2);
                    # Flash::info('¡Bienvenido <strong>' . Input::post('mail') . '</strong>!');
                    MyFlash::show('info', '¡Bienvenido <strong>' . Session::get('nick') . '</strong>!', TRUE);
                    return true;
                } 
                else 
                {
                    sleep(2);
                    # Flash::error('El usuario y/o la contraseña son incorrectos.');
                    MyFlash::show('danger', 'El usuario y/o la contraseña son incorrectos.', TRUE);
                }
            }
        }
        return false;
    }

    /**
     * Método para cerrar sesión
     * @return boolean
     */
    public function cerrarSesion() 
    {
        //Verifico si tiene sesión
        if (!self::isValid()) 
        {
            # Flash::error("No has iniciado sesión o ha caducado. <br /> Por favor identifícate nuevamente.");
            MyFlash::show('danger', 'No has iniciado sesión o ha caducado. <br /> Por favor identifícate nuevamente.', TRUE);
            return false;
        } 
        else 
        {
            //Registro la salida del sistema            
            # Acceso::setAcceso('salida', Session::get('id'));
            $auth = Auth2::factory('model');
            $auth->logout();
            //Elimino todas las variables de sesión de la app
            unset($_SESSION['KUMBIA_SESSION'][APP_PATH]);
            return true;
        }
    }

}
