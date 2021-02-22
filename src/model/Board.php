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

    public function toHTML()
    {
        $noteHtml = '';
        foreach ($this->notes->toArray() as $value) {
            $noteHtml = "$noteHtml
            {$value->toHTML()}
            ";
        }
        $html = "
        <div class=\"board\">
            <div class=\"board-header\">$this->name</div>
            <div class=\"board-body\">
                $noteHtml
            </div>
            <div class=\"footer\"
                <div class=\"created\"></div>
                <div class=\"modified\"></div>
            </div>
        </div>
        ";
        return $html;
    }

    public function toXML()
    {
        $noteXml = '';
        foreach ($this->notes->toArray() as $value) {
            $noteXml = "$noteXml
            {$value->toXML()}
            ";
        }
        $xml = "
        <board name=\"$this->name\" index=\"$this->id\">
            $noteXml
            <created>2018-07-31T18:30:00.000Z</created>
            <modified>2018-07-31T18:30:00.000Z</modified>
        </board>
        ";
        return $xml;
    }
}

?>
