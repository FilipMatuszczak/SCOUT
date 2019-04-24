<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TechnologiesRequests
 *
 * @ORM\Table(name="technologies_requests", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class TechnologiesRequests
{
    /**
     * @var int
     *
     * @ORM\Column(name="request_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $requestId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description_proposition", type="text", length=65535, nullable=false)
     */
    private $descriptionProposition;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     */
    private $timestamp;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="options", type="boolean", nullable=true)
     */
    private $options = '0';

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;


}
