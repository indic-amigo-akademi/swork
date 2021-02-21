<?php
class Note
{
    private int $id;
    private string $content;
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

    public function removeTag(string $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
        return $this;
    }

    public function addTag(string $tag, $key = ''): self
    {
        if (!$this->tags->contains($tag)) {
            if ($key == '') {
                $this->tags->add($tag);
            } else {
                $this->tags->set($key, $tag);
            }
        }
        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getContent(): string
    {
        return $this->content;
    }
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function toHTML()
    {
        $tagHtml = '';
        foreach ($this->tags->toArray() as $value) {
            $tagHtml = "$tagHtml
            <span class=\"tag\">$value</span>
            ";
        }
        $html = "
        <div class=\"note\">
            <div class=\"note-tags\">
                $tagHtml
            </div>
            <div class=\"note-body\">
                $this->content
            </div>
            <div class=\"note-footer\">
                <div class=\"date\">2018 Aug 1</div>
                <div class=\"created\"></div>
                <div class=\"modified\"></div>
            </div>
        </div>
        ";
        return $html;
    }

    public function toXML()
    {
        $tagXml = '';
        foreach ($this->tags->toArray() as $key => $value) {
            $tagXml = "$tagXml
            <tag name=\"$value\" index=\"$key\" />
            ";
        }
        $xml = "
        <note index=\"$this->id\">
            $tagXml
            <content>$this->content</content>
            <created>2018-07-31T18:30:00.000Z</created>
            <modified>2018-07-31T18:30:00.000Z</modified>
        </note>";
        return $xml;
    }
}
?>
