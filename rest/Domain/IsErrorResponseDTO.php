<?php


class IsErrorResponseDTO
{
    public bool $isError;
    public string $message;

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isError;
    }

    /**
     * @param bool $isError
     */
    public function setIsError(bool $isError): void
    {
        $this->isError = $isError;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function __construct()
    {
        $this->setMessage('');
        $this->setIsError(false);
    }

}