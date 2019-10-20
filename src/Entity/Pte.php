<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PteRepository")
 */
class Pte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finishdate;

    /**
     * @ORM\Column(type="integer")
     */
    private $spendingtime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $itemtype;

    /**
     * @return mixed
     */
    public function getItemtype()
    {
        return $this->itemtype;
    }

    /**
     * @param mixed $itemtype
     */
    public function setItemtype($itemtype): void
    {
        $this->itemtype = $itemtype;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(string $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getFinishdate(): ?\DateTimeInterface
    {
        return $this->finishdate;
    }

    public function setFinishdate(\DateTimeInterface $finishdate): self
    {
        $this->finishdate = $finishdate;

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
