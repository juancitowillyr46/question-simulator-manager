<?php


class PlanAssignedDetailDTO
{
    public int $id;
    public int $assignedPlanId;
    public int $examId;
    public int $numAttemptsAssigned;
    public int $numAttemptsMade;
    public string $attemptsMadeAt;
    public bool $attemptsCompleted;
    public ExamDTO $exam;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAssignedPlanId(): int
    {
        return $this->assignedPlanId;
    }

    /**
     * @param int $assignedPlanId
     */
    public function setAssignedPlanId(int $assignedPlanId): void
    {
        $this->assignedPlanId = $assignedPlanId;
    }

    /**
     * @return int
     */
    public function getExamId(): int
    {
        return $this->examId;
    }

    /**
     * @param int $examId
     */
    public function setExamId(int $examId): void
    {
        $this->examId = $examId;
    }

    /**
     * @return int
     */
    public function getNumAttemptsAssigned(): int
    {
        return $this->numAttemptsAssigned;
    }

    /**
     * @param int $numAttemptsAssigned
     */
    public function setNumAttemptsAssigned(int $numAttemptsAssigned): void
    {
        $this->numAttemptsAssigned = $numAttemptsAssigned;
    }

    /**
     * @return int
     */
    public function getNumAttemptsMade(): int
    {
        return $this->numAttemptsMade;
    }

    /**
     * @param int $numAttemptsMade
     */
    public function setNumAttemptsMade(int $numAttemptsMade): void
    {
        $this->numAttemptsMade = $numAttemptsMade;
    }

    /**
     * @return string
     */
    public function getAttemptsMadeAt(): string
    {
        return $this->attemptsMadeAt;
    }

    /**
     * @param string $attemptsMadeAt
     */
    public function setAttemptsMadeAt(string $attemptsMadeAt): void
    {
        $this->attemptsMadeAt = $attemptsMadeAt;
    }

    /**
     * @return ExamDTO
     */
    public function getExam(): ExamDTO
    {
        return $this->exam;
    }

    /**
     * @param ExamDTO $exam
     */
    public function setExam(ExamDTO $exam): void
    {
        $this->exam = $exam;
    }

    /**
     * @return bool
     */
    public function isAttemptsCompleted(): bool
    {
        return $this->attemptsCompleted;
    }

    /**
     * @param bool $attemptsCompleted
     */
    public function setAttemptsCompleted(bool $attemptsCompleted): void
    {
        $this->attemptsCompleted = $attemptsCompleted;
    }



    public function __construct(array $data = array())
    {
        if(count($data) > 0) {
            $this->setId($data['id']);
            $this->setAssignedPlanId($data['assigned_plan_id']);
            $this->setExamId($data['exam_id']);
            $this->setNumAttemptsAssigned($data['num_attempts_assigned']);
            $this->setNumAttemptsMade($data['num_attempts_made']);
            $this->setAttemptsMadeAt($data['attempts_made_at']);
//            if($this->getNumAttemptsMade() >= $this->getNumAttemptsAssigned()){
//                $this->setAttemptsCompleted(true);
//            } else {
//                $this->setAttemptsCompleted(false);
//            }
        }

    }

}