<?php


class ExamDTO
{
    public int $id;
    public string $uuid;
    public string $clientKey;
    public string $name;
    public int $approvalPercentage;
    public int $durationTimeSec;
    public int $totalQuestions;
    public string $description;
    public int $numAttemptsMade;
    public bool $attemptsCompleted;
    public string $language;
    public bool $supportSpanish;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * @param string $clientKey
     */
    public function setClientKey(string $clientKey): void
    {
        $this->clientKey = $clientKey;
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
     * @return int
     */
    public function getApprovalPercentage(): int
    {
        return $this->approvalPercentage;
    }

    /**
     * @param int $approvalPercentage
     */
    public function setApprovalPercentage(int $approvalPercentage): void
    {
        $this->approvalPercentage = $approvalPercentage;
    }

    /**
     * @return int
     */
    public function getDurationTimeSec(): int
    {
        return $this->durationTimeSec;
    }

    /**
     * @param int $durationTimeSec
     */
    public function setDurationTimeSec(int $durationTimeSec): void
    {
        $this->durationTimeSec = $durationTimeSec;
    }

    /**
     * @return int
     */
    public function getTotalQuestions(): int
    {
        return $this->totalQuestions;
    }

    /**
     * @param int $totalQuestions
     */
    public function setTotalQuestions(int $totalQuestions): void
    {
        $this->totalQuestions = $totalQuestions;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return bool
     */
    public function isSupportSpanish(): bool
    {
        return $this->supportSpanish;
    }

    /**
     * @param bool $supportSpanish
     */
    public function setSupportSpanish(bool $supportSpanish): void
    {
        $this->supportSpanish = $supportSpanish;
    }

    public function __construct(array $object)
    {
        $this->setId($object['id']);
        $this->setUuid($object['uuid']);
        $this->setClientKey($object['client_key']);
        $this->setName($object['name']);
        $this->setApprovalPercentage($object['approval_percentage']);
        $this->setDurationTimeSec($object['duration_time_sec']);
        $this->setTotalQuestions($object['total_questions']);
        $this->setDescription($object['description']);
        $this->setSupportSpanish($object['support_spanish']);
        $this->setLanguage($object['language']);

    }

}