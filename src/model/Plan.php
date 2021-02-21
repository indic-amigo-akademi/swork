<?php

class Plan
{
    private int $id;
    private string $name;
    private Collection $users, $boards;

    public function __construct()
    {
        $this->users = new Collection();
        $this->boards = new Collection();
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

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function removeBoard(Board $board): self
    {
        if ($this->boards->contains($board)) {
            $this->boards->removeElement($board);
        }
        return $this;
    }

    public function addBoard(Board $board): self
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
        }
        return $this;
    }

    public function getBoards(): Collection
    {
        return $this->boards;
    }

    public function toHTML()
    {
        $boardHtml = '';
        foreach ($this->boards->toArray() as $value) {
            $boardHtml = "$boardHtml
            {$value->toHTML()}
            ";
        }
        $html = "
        <div class=\"board-list\">
            <div>
                <span class=\"plan-name\">$this->name</span>(<span class=\"plan-id\">$this->id</span>)
            </div>
            $boardHtml
        </div>
        ";
        return $html;
    }

    public function toXML()
    {
        $boardXml = '';
        foreach ($this->boards->toArray() as $value) {
            $boardXml = "$boardXml
            {$value->toXML()}
            ";
        }
        $xml = "
        <plan name=\"$this->name\" index=\"$this->id\">
            $boardXml
        </plan>
        ";
        return $xml;
    }
}

?>
