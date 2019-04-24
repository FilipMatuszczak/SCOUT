<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Technologies
 *
 * @ORM\Table(name="technologies")
 * @ORM\Entity
 */
class Technologies
{
    /**
     * @var int
     *
     * @ORM\Column(name="technology_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $technologyId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="options", type="boolean", nullable=true)
     */
    private $options = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Projects", inversedBy="technology")
     * @ORM\JoinTable(name="projects_technologies",
     *   joinColumns={
     *     @ORM\JoinColumn(name="technology_id", referencedColumnName="technology_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="project_id")
     *   }
     * )
     */
    private $project;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Users", inversedBy="technology")
     * @ORM\JoinTable(name="users_technologies",
     *   joinColumns={
     *     @ORM\JoinColumn(name="technology_id", referencedColumnName="technology_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   }
     * )
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->project = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
