<?php

namespace App\Dto;

class MessageDisplay
{
    /** @var string */
    private $username;

    /** @var \DateTime */
    private $timestamp;

    /** @var string */
    private $message;

    /** @var bool */
    private $isCurrentUserSender;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return bool
     */
    public function getIsCurrentUserSender(): bool
    {
        return $this->isCurrentUserSender;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param bool $isCurrentUserSender
     */
    public function setIsCurrentUserSender(bool $isCurrentUserSender): void
    {
        $this->isCurrentUserSender = $isCurrentUserSender;
    }
}