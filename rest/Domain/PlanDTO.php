<?php


class PlanDTO
{
    public int $id;
    public int $assignedPlanId;
    public string $name;
    public string $dateExpiry;
    public int $expirationDays;
    public array $exams;
    public array $detail;
    public bool $expired;
    public int $numberAttempts;
    public bool $supportTranslation;

    public function __construct(array $plan = array(), array $assignedPlan = array())
    {
        if(count($plan) > 0 && count($assignedPlan) > 0){
            $this->setSupportTranslation($plan['support_translation']);
            $this->setNumberAttempts($plan['number_attempts']);
            $this->setName($plan['name']);
            $this->setExpirationDays($plan['expiration_days']);
            $this->setId($plan['id']);
            $this->setDateExpiry($assignedPlan['date_expiry']);
            $this->setSupportTranslation($plan['support_translation']);
            $this->setAssignedPlanId($assignedPlan['id']);
        }
    }

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDateExpiry(): string
    {
        return $this->dateExpiry;
    }

    /**
     * @param string $dateExpiry
     */
    public function setDateExpiry(string $dateExpiry): void
    {
        $this->dateExpiry = $dateExpiry;
    }

    /**
     * @return array
     */
    public function getDetail(): array
    {
        return $this->detail;
    }

    /**
     * @param array $detail
     */
    public function setDetail(array $detail): void
    {
        $this->detail = $detail;
    }

    /**
     * @return int
     */
    public function getExpirationDays(): int
    {
        return $this->expirationDays;
    }

    /**
     * @param int $expirationDays
     */
    public function setExpirationDays(int $expirationDays): void
    {
        $this->expirationDays = $expirationDays;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired;
    }

    /**
     * @param bool $expired
     */
    public function setExpired(bool $expired): void
    {
        $this->expired = $expired;
    }

    /**
     * @return int
     */
    public function getNumberAttempts(): int
    {
        return $this->numberAttempts;
    }

    /**
     * @param int $numberAttempts
     */
    public function setNumberAttempts(int $numberAttempts): void
    {
        $this->numberAttempts = $numberAttempts;
    }

    /**
     * @return bool
     */
    public function isSupportTranslation(): bool
    {
        return $this->supportTranslation;
    }

    /**
     * @param bool $supportTranslation
     */
    public function setSupportTranslation(bool $supportTranslation): void
    {
        $this->supportTranslation = $supportTranslation;
    }

    /**
     * @return array
     */
    public function getExams(): array
    {
        return $this->exams;
    }

    /**
     * @param array $exams
     */
    public function setExams(array $exams): void
    {
        $this->exams = $exams;
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


}