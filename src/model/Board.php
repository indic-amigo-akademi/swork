<?php

class Board
{
    private int $id;
    private string $name;
    private Collection $notes;
    public function __construct()
    {
        $this->notes = new Collection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
        }
        return $this;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
        }
        return $this;
    }

    public function getNotes(): Collection
    {
        return $this->notes;
    }
}

?>
