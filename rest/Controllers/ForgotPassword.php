<?php


class MyControllerForgotPassword extends modRestController
{
    public array $allowedMethods = array('POST');

    public function post()
    {
        // Find User
        $getUser = $this->modx->getObject('modUser', array('username' => $this->getProperty('username')));
        if($getUser != null) {

            // Get Profile
            $profile = $getUser->getOne('Profile');
            $fullName = $profile->get('fullname');
            $email = $profile->get('email');

            $jwt = new SecurityToken($this->modx);

            $accessToken = $jwt->encodeJwt(array(
                'data' => array(
                    'id'       => $getUser->get('id'),
                    'email'    => $email,
                    'fullName' => $fullName,
                    'type'     => 'FORGOT_PASSWORD'
                )
            ));

            // Tpl Body
            $tplOutput = $this->modx->getChunk('extEmailForgotPassword',array(
                'fullname' => $fullName,
                'url' => $this->modx->getOption('ext_forgot_password_url'),
                'token' => $accessToken
            ));

            // Send Email
            $this->modx->getService('mail', 'mail.modPHPMailer');
            $this->modx->mail->set(modMail::MAIL_BODY, $tplOutput);
            $this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
            $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
            $this->modx->mail->set(modMail::MAIL_SENDER, $this->modx->getOption('emailsender'));
            $this->modx->mail->set(modMail::MAIL_SUBJECT, $this->modx->getOption('login.forgot_password_email_subject'));
            $this->modx->mail->address('to', $email, $fullName);
            $this->modx->mail->setHTML(true);
            $sent = $this->modx->mail->send();

            if(!$sent) {
                throw new Exception(WebUtils::FORGOT_PASSWORD_ERROR_EMAIL, 401);
            } else {
                $this->success(WebUtils::FORGOT_PASSWORD_SUCCESS_EMAIL, array(), 200);
            }

        } else {
            throw new Exception(WebUtils::FORGOT_PASSWORD_VERIFY_EMAIL, 401);
        }
    }
}