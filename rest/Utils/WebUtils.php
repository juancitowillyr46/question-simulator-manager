<?php


class WebUtils
{
    // Login
    const LOGIN_ERROR_MESSAGE = 'El nombre de usuario o la contraseña son incorrectos. Verifique el nombre de usuario, vuelva a escribir la contraseña e intente nuevamente.';

    // Reset Password
    const RESET_PASSWORD = 'Su tiempo para cambiar su contraseña terminó';
    const RESET_PASSWORD_SUCCESS = 'Tu clave se cambio correctamente';

    // Forgot Password
    const FORGOT_PASSWORD_VERIFY_EMAIL = 'El correo electrónico no existe';
    const FORGOT_PASSWORD_SUCCESS_EMAIL = 'Su solicitud se envío correctamente a su correo electrónico';
    const FORGOT_PASSWORD_ERROR_EMAIL = 'No se puedo enviar las instrucciones a su correo electrónico, intente nuevamente';

    const SUCCESS_GENERIC = 'OK';
    const ERROR_GENERIC = 'Ocurrió un problema, intento nuevamente';

    const CHANGE_PASSWORD_SUCCESS = 'La clave se cambio correctamente';
    const CHANGE_PASSWORD_ERROR = 'La antigua clave no coincide';
}