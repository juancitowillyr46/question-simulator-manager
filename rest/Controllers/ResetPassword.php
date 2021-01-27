<?php


class MyControllerResetPassword extends modRestController
{
    public function post()
    {

        $jwt = new SecurityToken($this->modx);

        try {

            // Verify Token and Get Payload
            $payload = $jwt->verifyToken($this->request->headers);

            if(!is_null($payload)) {

                $accessToken = $jwt->getAccessToken();
                $payLoad = (array) $jwt->decodeJwt($accessToken);
                $password  = $this->getProperty('password');

                $getUser = $this->modx->getObject('modUser', array('id' => $payLoad['data']->id));

                if($getUser != null) {

                    // Change Password
                    $getUser->set('password', $password);
                    $success = $getUser->save();
                    $this->success(WebUtils::RESET_PASSWORD_SUCCESS, array('sendEmail' => $success), 200);
                }

            } else {
                throw new Exception(WebUtils::ERROR_GENERIC, 401);
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 401);
        }

//        $isError = false;
//        // $headers = $this->request->headers;
//
//
//        //$this->getProperty('accessToken');
//
//
//
//
//
//
//
//        $getUser = $this->modx->getObject('modUser', array('id' => $payLoad['data']->id));
//        if($getUser != null) {
//
//            // Change Password
//            $getUser->set('password', $password);
//            $success = $getUser->save();
//
//            if($success) {
//                // Tpl Body
//                $tplOutput = $this->modx->getChunk('extResetPasswordEmail', array(
//                    'fullname' => $fullname,
//                    'password' => $password
//                ));
//
//                // Send Email
//                /*$this->modx->getService('mail', 'mail.modPHPMailer');
//                $this->modx->mail->set(modMail::MAIL_BODY, $tplOutput);
//                $this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
//                $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
//                $this->modx->mail->set(modMail::MAIL_SENDER, $this->modx->getOption('emailsender'));
//                $this->modx->mail->set(modMail::MAIL_SUBJECT, $this->modx->getOption('login.forgot_password_email_subject'));
//                $this->modx->mail->address('to', $email, $fullname);
//                $this->modx->mail->setHTML(true);
//                $sent = $this->modx->mail->send();*/
//                $sent = true;
//
//                if(!$sent) {
//                    $isError = true;
//                }
//            } else {
//                $isError = true;
//            }
//        } else {
//            $isError = true;
//        }
//
//        if(!$isError) {
//            $this->success('', array('sendEmail' => 'OK'), 200);
//        } else {
//            $this->failure('', array(), 404);
//        }
    }
}