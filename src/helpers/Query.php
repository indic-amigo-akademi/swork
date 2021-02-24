<?php

class Query
{
    private $connection, $hostname, $username, $password, $database;

    public function __construct(
        $hostname = 'localhost',
        $username = 'root',
        $password = '',
        $database = 'swork'
    ) {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        try {
            $this->connection = new PDO(
                "mysql:host=$this->hostname;dbname=$this->database;charset=utf8",
                $this->username,
                $this->password
            );

            // set the PDO error mode to exception
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            print 'Connected successfully' . PHP_EOL;
        } catch (Exception $e) {
            print 'Connection failed: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function createTable(
        $tablename = 'users',
        $fields,
        $timestamps = false,
        $drop = false
    ) {
        $fieldQuery = [];
        $query = null;
        foreach ($fields as $field) {
            array_push($fieldQuery, join(' ', $field));
        }

        if ($timestamps) {
            array_push(
                $fieldQuery,
                'created TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            );
        }

        if ($drop) {
            $query = "DROP TABLE IF EXISTS `$tablename`;";
        }
        $query .= sprintf(
            'CREATE TABLE IF NOT EXISTS %s (%s);',
            $tablename,
            join(',', $fieldQuery)
        );

        try {
            $this->connection->exec($query);
            print "Table $tablename created successfully" . PHP_EOL;
        } catch (Exception $e) {
            print "Creating $tablename failed: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function insert($tablename = 'users', $values)
    {
        if (!isset($values) || sizeof($values) <= 0) {
            print 'Invalid values passed for insertion' . PHP_EOL;
            return;
        }

        $query = null;
        $fieldList = [];
        $valueList = [];
        $dataList = [];

        if (is_array(reset($values))) {
            foreach ($values as $value) {
                $this->insert($tablename, $value);
            }
        } else {
            foreach ($values as $key => $value) {
                if (is_numeric($value) || is_string($value)) {
                    array_push($fieldList, $key);
                    array_push($valueList, ":$key");
                    $dataList = array_merge($dataList, [$key => $value]);
                }
            }
            $query = $this->connection->prepare(
                sprintf(
                    'INSERT INTO `%s` (%s) VALUES (%s);',
                    $tablename,
                    join(', ', $fieldList),
                    join(', ', $valueList)
                )
            );

            try {
                $query->execute($dataList);
                print "Table $tablename inserted successfully" . PHP_EOL;
            } catch (Exception $e) {
                print "Insertion to $tablename failed: " .
                    $e->getMessage() .
                    PHP_EOL;
            }
        }
    }

    public function deleteBy($tablename = 'users', $values)
    {
        $query = sprintf('DELETE FROM `%s`', $tablename);
        $where = [];
        if (reset($values)) {
        }
        foreach ($values as $key => $value) {
            if (is_numeric($value)) {
                array_push($where, "`$key` = $value");
            } elseif (is_string($value)) {
                $value = mysqli_escape_string($this->connection, $value);
                array_push($where, "`$key` = '$value'");
            } elseif (is_array($value)) {
                $arr = $value;
                $or_arr = [];
                foreach ($arr as $param) {
                    if (is_numeric($value)) {
                        array_push($or_arr, "`$key` = $param");
                    } elseif (is_string($param)) {
                        $value = mysqli_escape_string(
                            $this->connection,
                            $value
                        );
                        array_push($or_arr, "`$key` = '$param'");
                    }
                }
                array_push($where, join(' OR ', $or_arr));
            }
        }
        $where = join(' AND ', $where);
        $query = $query . ' WHERE ' . $where;
        try {
            $this->connection->exec($query);
            print "Table $tablename deleted successfully" . PHP_EOL;
        } catch (Exception $e) {
            print "Deleting $tablename failed: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function updateBy($tablename = 'users', $values, $newValues)
    {
        $query = sprintf('UPDATE FROM `%s`', $tablename);
        $where = [];
        foreach ($values as $key => $value) {
            if (is_numeric($value)) {
                array_push($where, "`$key` = $value");
            } elseif (is_string($value)) {
                array_push($where, "`$key` = '$value'");
            } elseif (is_array($value)) {
                $arr = $value;
                $or_arr = [];
                foreach ($arr as $param) {
                    if (is_numeric($value)) {
                        array_push($or_arr, "`$key` = $param");
                    } elseif (is_string($param)) {
                        array_push($or_arr, "`$key` = '$param'");
                    }
                }
                array_push($where, join(' OR ', $or_arr));
            }
        }
        $where = join(' AND ', $where);
        $query = $query . ' WHERE ' . $where;
        try {
            $this->connection->exec($query);
            print "Table $tablename deleted successfully" . PHP_EOL;
        } catch (Exception $e) {
            print "Deleting $tablename failed: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function findBy($tablename = 'users', $values)
    {
    }

    public function is_live()
    {
        return isset($this->connection);
    }

    public function reconnect()
    {
        try {
            $this->connection = new PDO(
                "mysql:host=$this->hostname;dbname=$this->database;charset=utf8",
                $this->username,
                $this->password
            );

            // set the PDO error mode to exception
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            print 'Connected successfully' . PHP_EOL;
        } catch (Exception $e) {
            print 'Connection failed: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function close()
    {
        $this->connection = null;
    }
}

?>
