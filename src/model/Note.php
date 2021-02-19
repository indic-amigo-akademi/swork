<?php
class Note
{
    private int $id;
    private Collection $tags;
    public function __construct()
    {
        $this->tags = new Collection();
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


    public function removeNote(string $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
        return $this;
    }

    public function addNote(string $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }

    public function getNotes(): Collection
    {
        return $this->tags;
    }
}
?>
