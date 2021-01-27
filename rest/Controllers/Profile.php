<?php


class MyControllerProfile extends modRestController
{
    public array $allowedMethods = array('POST');

    public function post() {

        $jwt = new SecurityToken($this->modx);

        try {

            $payload = $jwt->verifyToken($this->request->headers);
            if(!is_null($payload)) {

                $userService = new UsersService($this->modx);
                $loginDto = $userService->getProfile($payload->id);
                $loginDto = (array) $loginDto;

                $this->success(WebUtils::SUCCESS_GENERIC, $loginDto, 200);

            } else {
                throw new Exception(WebUtils::ERROR_GENERIC, 401);
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 401);
        }

    }

    public function get() {

        $this->failure('', array(), 401);

//        if($this->getProperty('id')) {
//
//            //$this->modx->addPackage('pgksimulador', MODX_CORE_PATH . 'components/pgksimulador/model/', '');
//
//            $objUser = $this->modx->getObject('modUser', array('id' => $this->getProperty('id')));
//            //$extUser = $objUser->getOne('extUsers');
//            //$AssignedPlan = $extUser->getMany('AssignedPlan');
//
//
//            // $extUser = $this->modx->getObject('extUsers');
//            //$extUser->fromArray($fields, "", true, true);
//
//            //$data = $objUser->getOne('AssignedPlan');
//
//            //$data = $objUser->getOne('AssignedPlan');
//
////            $extUser1 = $this->modx->getObject('UserAssignedPlan', array('plan_id' => 1)); // where 123 is the id of a user
//            $extUser2 = $this->modx->getObject('extUser', array('id' => 19));
//            $assignedPlan = $extUser2->getOne('SimUserAssignedPlan');// where 123 is the id of a user
//            //$ddd = $assignedPlan->getOne('SimPlans');
//            $ddd = $assignedPlan->toArray();
//
//            if($objUser) {
//                $response['object'] = $objUser->toArray();
//                $profile = $objUser->getOne('Profile');
////                $data = $objUser->getOne('AssignedPlan');
//                $response['object']['profile'] = (is_null($profile))? [] : $profile->toArray();
//                $this->success($response['message'], $response['object'], 200);
//            } else {
//                $this->failure('', array(), 401);
//            }
//
//        } else {
//            $this->failure('', array(), 401);
//        }
    }
}