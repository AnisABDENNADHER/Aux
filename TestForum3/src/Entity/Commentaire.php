<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Contenu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Parent_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Thread", inversedBy="Commentaires")
     */
    private $Thread;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(string $Contenu): self
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->Parent_id;
    }

    public function setParentId(?int $Parent_id): self
    {
        $this->Parent_id = $Parent_id;

        return $this;
    }

    public function getThread(): ?string
    {
        return $this->Thread;
    }

    public function setThread(string $Thread): self
    {
        $this->Thread = $Thread;

        return $this;
    }
}
