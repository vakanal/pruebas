<?php

class Users extends ActiveRecord 
{

    protected function initialize()
    {
        $this->validates_email_in('mail');
    }

    protected function before_save()
    {
        if (empty($this->pass))
        {
            $this->pass = Session::get('pass');
        }
        else
        {
            $this->pass = hash('sha3-512', $this->pass);
        }
    }

    protected function after_save()
    {
        Session::set('nick', $this->nick);
        Session::set('mail', $this->mail);
        if (!empty($this->pass))
        {
            Session::set('pass', $this->pass);
        }
    }

    public function getUser($id)
    {
        return $this->find($id);
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
                    sleep(1);
                    # Flash::info('¡Bienvenido <strong>' . Input::post('mail') . '</strong>!');
                    MyFlash::show('success', '¡Bienvenido <strong>' . Session::get('nick') . '</strong>!', TRUE);
                    return true;
                } 
                else 
                {
                    sleep(1);
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

    /**
     * Guarda un usuario y sube la foto de un usuario.
     *
     * @param array $data Arreglo con los datos de usuario
     * @return boolean
     * @throws Exception
     */
    public function saveWithPhoto($data)
    {
        //Inicia la transacción
        $this->begin();
        //Intenta crear el usuario con los datos pasados
        if ($this->create($data))
        {
            //Intenta subir y actualizar la foto
            if ($this->updatePhoto())
            {
                //Se confirma la transacción
                $this->commit();
                return true;
            }
        }
        //Si algo falla se regresa la transacción
        $this->rollback();
        return false;
    }

    /**
     * Sube y actualiza la foto del usuario.
     *
     * @return boolean|null
     */
    public function updatePhoto()
    {
        //Intenta subir la foto que viene en el campo 'photo'
        if ($photo = $this->uploadPhoto('photo'))
        {
            //Modifica el campo photo del usuario y lo intenta actualizar
            $this->photo = $photo;
            return $this->update();
        }
    }

    /**
     * Sube la foto y retorna el nombre del archivo generado.
     *
     * @param string $imageField Nombre de archivo recibido por POST
     * @return string|false
     */
    public function uploadPhoto($imageField)
    {
        //Usamos el adapter 'image'
        $file = Upload::factory($imageField, 'image');
        //le asignamos las extensiones a permitir
        $file->setExtensions(array('jpg', 'png', 'gif', 'jpeg'));
        //Intenta subir el archivo
        if ($file->isUploaded())
        {
            //Lo guarda usando un nombre de archivo aleatorio y lo retorna.
            return $file->saveRandom();
        }
        //Si falla al subir
        return false;
    }

}
