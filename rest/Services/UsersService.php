<?php


class UsersService
{
    public Modx $modx;

    public function __construct(Modx $modx)
    {
        $this->modx = $modx;
    }

    public function getProfile($id) {

        $user = $this->modx->getObject('modUser', $id);

        // Get Profile
        $profile = $user->getOne('Profile');

        // Get Group
        $group = $this->modx->getObject('modUserGroup', $user->get('primary_group'));
        $roleDto = new RoleDTO();
        $roleDto->setRoleId($group->get('id'));
        $roleDto->setRoleName($group->get('name'));

        // Get Assigned Plan
        $assignedPlan = $this->modx->getObject('SimUserAssignedPlan', array('user_id' => $user->get('id')));
        $associatedExams = $assignedPlan->get('associated_exams');

        // Get Plan
        $plan = $assignedPlan->getOne('SimPlans');
        $planDto = new PlanDTO($plan->toArray(), $assignedPlan->toArray());

        // Get Exams
        $lstExams = [];
        $arrExams = explode('||', $associatedExams);
        foreach ($arrExams as $arrExam) {

            // Get Exam
            $objExam = $this->modx->getObject('SimExams', array('id' => (int) $arrExam));
            $examDto = new ExamDTO($objExam->toArray());

            // Get Detail
            $ap = $assignedPlan->get('id');
            $ex = (int) $arrExam;
            $objApDetail = $this->modx->getObject('SimUserApDetail', array(
                    'assigned_plan_id' => $assignedPlan->get('id'),
                    'exam_id'          => (int) $arrExam
                )
            );
            $examDto->setNumAttemptsMade((!is_null($objApDetail))? $objApDetail->get('num_attempts_made') : 0);

            // Validate Complete Exams
            if($examDto->getNumAttemptsMade() >= (int) $plan->get('number_attempts')){
                $examDto->setAttemptsCompleted(true);
            } else {
                $examDto->setAttemptsCompleted(false);
            }

            $lstExams[] = $examDto;
        }

        $planDto->setExams($lstExams);

        // Validate Expiration Date
        $now = new DateTime();
        $expiredDay = strtotime($assignedPlan->get('date_expiry'));
        $currentDate = $now->getTimestamp();
        if($currentDate > $expiredDay) {
            $planDto->setExpired(true);
        } else {
            $planDto->setExpired(false);
        }

        // Dto login
        $userProfile = new UserProfileDTO();
        $userProfile->setId($profile->get('id'));
        $userProfile->setEmail($profile->get('email'));
        $userProfile->setFullname($profile->get('fullname'));
        $userProfile->setToken('');
        $userProfile->setPlan($planDto);
        $userProfile->setRole($roleDto);
        return $userProfile;
    }
}