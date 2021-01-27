<?php


class PlanAssignedDTO
{
    public string $dateExpiry;
    public PlanAssignedDetailDTO $detail;

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
     * @return PlanAssignedDetailDTO
     */
    public function getDetail(): PlanAssignedDetailDTO
    {
        return $this->detail;
    }

    /**
     * @param PlanAssignedDetailDTO $detail
     */
    public function setDetail(PlanAssignedDetailDTO $detail): void
    {
        $this->detail = $detail;
    }


}