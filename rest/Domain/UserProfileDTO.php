<?php


class UserProfileDTO
{
    public int $id;
    public string $email;
    public string $fullName;
    public string $token;
    public PlanDTO $plan;
    public RoleDTO $role;

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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return PlanDTO
     */
    public function getPlan(): PlanDTO
    {
        return $this->plan;
    }

    /**
     * @param PlanDTO $plan
     */
    public function setPlan(PlanDTO $plan): void
    {
        $this->plan = $plan;
    }

    /**
     * @return RoleDTO
     */
    public function getRole(): RoleDTO
    {
        return $this->role;
    }

    /**
     * @param RoleDTO $role
     */
    public function setRole(RoleDTO $role): void
    {
        $this->role = $role;
    }


}