<?php


class MyControllerChangePassword extends modRestController
{
    public function post() {

        $jwt = new SecurityToken($this->modx);

        try {

            $jwt->verifyToken($this->request->headers);

            $accessToken = $jwt->getAccessToken();
            $oldPassword = $this->getProperty('oldPassword');
            $newPassword = $this->getProperty('newPassword');

            $payLoad = (array) $jwt->decodeJwt($accessToken);

            // Get User
            $getUser = $this->modx->getObject('modUser', array("id" => $payLoad['data']->id));
            $success = $getUser->changePassword($newPassword, $oldPassword);

            if($success){
                $this->success(WebUtils::CHANGE_PASSWORD_SUCCESS, array(), 200);
            } else {
                throw new Exception(WebUtils::CHANGE_PASSWORD_ERROR, 404);
            }


        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 401);
        }

    }
}