<?php


class MyControllerRegister extends modRestController
{
    public array $allowedMethods = array('POST');

    const REGISTER_GROUPS = 'Students:Member';
    const REGISTER_ACTIVATION = false;
    const REGISTER_JSON_RESPONSE = true;
    const REGISTER_POST_HOOKS = 'snpUpdateAttributesUser,snpSignUpSendEmail';

    public function post() {

        $payload = $this->getProperties();
        array_push($payload, array('groups' => 'Students'));
        $_POST = $payload;

        return $this->modx->runSnippet('Register',array(
            'usernameField'   => 'username',
            'passwordField'   => 'password',
            'emailField'      => 'email',
            'jsonResponse'    => self::REGISTER_JSON_RESPONSE,
            'usergroupsField' => 'groups',
            'usergroups'      => self::REGISTER_GROUPS,
            'activation'      => self::REGISTER_ACTIVATION,
            'postHooks'       => self::REGISTER_POST_HOOKS
        ));
    }

}