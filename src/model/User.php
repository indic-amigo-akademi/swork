<?php

class User
{
    private int $id;
    private string $username, $password;

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function setPassword($password): self
    {
        $this->password = $password;
        return $this;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setUserName($username): self
    {
        $this->username = $username;
        return $this;
    }
    public function getUserName(): string
    {
        return $this->username;
    }
}

?>
