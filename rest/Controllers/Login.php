<?php


class MyControllerLogin extends modRestController
{
    const LOGIN_CONTEXT = 'web';
    const LOGIN_REMEMBER_ME = false;

    public array $allowedMethods = array('POST');

    public function post() {

        $isError = false;
        $loginDto = array();
        $properties = array(
            'login_context' => self::LOGIN_CONTEXT,
            'add_contexts'  => $this->getProperty('contexts',''),
            'username'      => $this->getProperty('username'),
            'password'      => $this->getProperty('password'),
            'returnUrl'     => '',
            'rememberme'    => self::LOGIN_REMEMBER_ME
        );

       $response = $this->modx->runProcessor('security/login', $properties);
       $success = $response->response['success'];

       if($success == false) {

           $isError = true;

       } else {

            $user = $this->modx->getAuthenticatedUser('web');

            if(!is_null($user)) {

                 $userService = new UsersService($this->modx);
                 $loginDto = $userService->getProfile($user->get('id'));
                 $loginDto = (array) $loginDto;

            } else {
               $isError = true;
            }
       }

        if($isError){
            $this->failure(WebUtils::LOGIN_ERROR_MESSAGE, array(), 401);
        } else {
            $this->success($response->response['message'], $loginDto);
        }

    }

    public function get()
    {
        $this->failure('', array(), 404);
    }

    public function put()
    {
        $this->failure('', array(), 404);
    }

    public function delete()
    {
        $this->failure('', array(), 404);
    }
}