<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TutorialRepository")
 */
class Tutorial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $attenddate;

    /**
     * @ORM\Column(type="integer")
     */
    private $spendingtime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttenddate(): ?\DateTimeInterface
    {
        return $this->attenddate;
    }

    public function setAttenddate(\DateTimeInterface $attenddate): self
    {
        $this->attenddate = $attenddate;

        return $this;
    }

    public function getSpendingtime(): ?int
    {
        return $this->spendingtime;
    }

    public function setSpendingtime(int $spendingtime): self
    {
        $this->spendingtime = $spendingtime;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
