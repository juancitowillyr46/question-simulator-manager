<?php


class MyControllerAttempts extends modRestController
{
    public function post()
    {

        $jwt = new SecurityToken($this->modx);

        try {

            $payload = $jwt->verifyToken($this->request->headers);
            if(!is_null($payload)) {

                // Request Data
                $assignedPlanId = (int) $this->getProperty('assignedPlanId');
                $examId = (int) $this->getProperty('examId');

                $attemptsMade = 1;

                // Find Detail
                $objApDetail = $this->modx->getObject('SimUserApDetail', array(
                        'assigned_plan_id' => (int) $assignedPlanId,
                        'exam_id'          => (int) $examId
                    )
                );

                if(!is_null($objApDetail)) {

                    // Validate Attempts
                    $getObjAp = $this->modx->getObject('SimUserAssignedPlan', array('id' => $assignedPlanId));
                    $objPlan = $getObjAp->getOne('SimPlans');
                    if($objPlan->get('number_attempts') == $objApDetail->get('num_attempts_made')) {
                        throw new Exception('Superaste el número de intentos', 404);
                    }

                    // Edit Attempts
                    $attemptsMade = (int) $objApDetail->get('num_attempts_made') + 1;
                    $objApDetail->set('attempts_made_at', date('Y-m-d H:i:s'));

                } else {

                    // New Attempts
                    $objApDetail = $this->modx->newObject('SimUserApDetail');
                    $objApDetail->set('assigned_plan_id', $assignedPlanId);
                    $objApDetail->set('exam_id', $examId);

                }

                // Done Change
                $objApDetail->set('num_attempts_made', $attemptsMade);
                $objApDetail->set('attempts_made_at', date('Y-m-d H:i:s'));
                if($objApDetail->save()){
                    $this->success('Ok', $objApDetail->toArray(), 200);
                }

            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }


    }
}