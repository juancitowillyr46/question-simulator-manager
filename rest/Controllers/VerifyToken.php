<?php


class MyControllerVerifyToken  extends modRestController
{
    public function post()
    {
        $jwt = new SecurityToken($this->modx);

        try {

            // Get Payload
            $payload = $jwt->verifyToken($this->request->headers);

            return is_object($payload);

        } catch (Exception $e) {
            throw new Exception(WebUtils::RESET_PASSWORD, 404);
        }

    }
}