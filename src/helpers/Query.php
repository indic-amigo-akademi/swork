<?php

class Query
{
    private $connection, $variables;
    public function __construct($database, $variables)
    {
        $this->connection = $database;
        $this->variables = $variables;
    }

    public function createTable()
    {
    }

    public function insert()
    {
    }

    public function deleteBy()
    {
    }

    public function updateBy()
    {
    }

    public function close()
    {
    }
}

?>
