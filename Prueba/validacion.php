<?php
class Validacion {
    public static function validarnombre($nombre) {
        return preg_match("/^[a-zA-Z\s]+$/", $nombre);
    }

    public static function validarapellidos($apellidos) {
        return preg_match("/^[a-zA-Z\s]+$/", $apellidos);
    }

    public static function calidarcorreo($correo) {
        return preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $correo);
    }

    public static function validarid($id) {
        return preg_match("/^[0-9]+$/", $id);
    }

}
?>
