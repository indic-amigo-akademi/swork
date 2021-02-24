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
            error_log('Connected successfully' . PHP_EOL);
        } catch (Exception $e) {
            error_log('Connection failed: ' . $e->getMessage() . PHP_EOL);
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
            error_log("Table $tablename created successfully" . PHP_EOL);
            return true;
        } catch (Exception $e) {
            error_log(
                "Creating $tablename failed: " . $e->getMessage() . PHP_EOL
            );
            return false;
        }
    }

    public function insert($tablename = 'users', $values)
    {
        if (!isset($values) || sizeof($values) <= 0) {
            error_log('Invalid values passed for insertion' . PHP_EOL);
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
                error_log("Table $tablename inserted successfully" . PHP_EOL);
                return true;
            } catch (Exception $e) {
                error_log(
                    "Insertion to $tablename failed: " .
                        $e->getMessage() .
                        PHP_EOL
                );
                return false;
            }
        }
    }

    public function deleteBy($tablename = 'users', $search)
    {
        $query = sprintf('DELETE FROM `%s`', $tablename);
        $where = [];
        $dataList = [];

        foreach ($search as $key => $value) {
            if (is_numeric($value) || is_string($value)) {
                array_push($where, "`$key` = ?");
                array_push($dataList, $value);
            } elseif (is_array($value)) {
                $arr = $value;
                $or_arr = [];
                foreach ($arr as $param) {
                    if (is_numeric($param) || is_string($param)) {
                        array_push($or_arr, "`$key` = ?");
                        array_push($dataList, $param);
                    }
                }
                array_push($where, join(' OR ', $or_arr));
            }
        }
        $where = join(' AND ', $where);
        $query = $this->connection->prepare($query . ' WHERE ' . $where);
        try {
            $query->execute($dataList);
            error_log("Table $tablename deleted successfully" . PHP_EOL);
            return true;
        } catch (Exception $e) {
            error_log(
                "Deleting $tablename failed: " . $e->getMessage() . PHP_EOL
            );
            return false;
        }
    }

    public function updateBy($tablename = 'users', $search, $values)
    {
        $query = sprintf('UPDATE `%s`', $tablename);
        $setList = [];
        $where = [];
        $dataList = [];

        if (isset($values)) {
            foreach ($values as $key => $value) {
                if (is_numeric($value) || is_string($value)) {
                    array_push($setList, "$key=?");
                    array_push($dataList, $value);
                }
            }
            $setList = join(' , ', $setList);
            $query .= ' SET ' . $setList;
        }

        if (isset($search)) {
            foreach ($search as $key => $value) {
                if (is_numeric($value) || is_string($value)) {
                    array_push($where, "`$key` = ?");
                    array_push($dataList, $value);
                } elseif (is_array($value)) {
                    $arr = $value;
                    $or_arr = [];
                    foreach ($arr as $param) {
                        if (is_numeric($param) || is_string($param)) {
                            array_push($or_arr, "`$key` = ?");
                            array_push($dataList, $param);
                        }
                    }
                    array_push($where, join(' OR ', $or_arr));
                }
            }
            $where = join(' AND ', $where);
            $query .= ' WHERE ' . $where;
        }
        $query = $this->connection->prepare($query);
        try {
            $query->execute($dataList);
            error_log("Table $tablename updated successfully" . PHP_EOL);
            return true;
        } catch (Exception $e) {
            error_log(
                "Updating $tablename failed: " . $e->getMessage() . PHP_EOL
            );
            return false;
        }
    }

    public function findAllBy(
        $tablename = 'users',
        $search = null,
        $select = null
    ) {
        if (isset($select)) {
            if (is_array($select)) {
                $select = join(',', $select);
            }
            $query = sprintf('SELECT %s FROM `%s`', $select, $tablename);
        } else {
            $query = sprintf('SELECT * FROM `%s`', $tablename);
        }
        $where = [];
        $dataList = [];

        if (isset($search)) {
            foreach ($search as $key => $value) {
                if (is_numeric($value) || is_string($value)) {
                    array_push($where, "`$key` = ?");
                    array_push($dataList, $value);
                } elseif (is_array($value)) {
                    $arr = $value;
                    $or_arr = [];
                    foreach ($arr as $param) {
                        if (is_numeric($param) || is_string($param)) {
                            array_push($or_arr, "`$key` = ?");
                            array_push($dataList, $param);
                        }
                    }
                    array_push($where, join(' OR ', $or_arr));
                }
            }
            $where = join(' AND ', $where);
            $query .= ' WHERE ' . $where;
        }
        $query = $this->connection->prepare($query);
        try {
            $query->execute($dataList);
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log(
                "Selecting $tablename failed: " . $e->getMessage() . PHP_EOL
            );
            return null;
        }
    }

    public function findOneBy(
        $tablename = 'users',
        $search = null,
        $select = null
    ) {
        if (isset($select)) {
            if (is_array($select)) {
                $select = join(',', $select);
            }
            $query = sprintf('SELECT %s FROM `%s`', $select, $tablename);
        } else {
            $query = sprintf('SELECT * FROM `%s`', $tablename);
        }
        $where = [];
        $dataList = [];

        if (isset($search)) {
            foreach ($search as $key => $value) {
                if (is_numeric($value) || is_string($value)) {
                    array_push($where, "`$key` = ?");
                    array_push($dataList, $value);
                } elseif (is_array($value)) {
                    $arr = $value;
                    $or_arr = [];
                    foreach ($arr as $param) {
                        if (is_numeric($param) || is_string($param)) {
                            array_push($or_arr, "`$key` = ?");
                            array_push($dataList, $param);
                        }
                    }
                    array_push($where, join(' OR ', $or_arr));
                }
            }
            $where = join(' AND ', $where);
            $query .= ' WHERE ' . $where;
        }
        $query = $this->connection->prepare($query);
        try {
            $query->execute($dataList);
            return $query->fetch();
        } catch (Exception $e) {
            error_log(
                "Selecting $tablename failed: " . $e->getMessage() . PHP_EOL
            );
            return false;
        }
    }

    public function is_live()
    {
        return isset($this->connection) && !is_null($this->connection);
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
            error_log('Connected successfully' . PHP_EOL);
            return true;
        } catch (Exception $e) {
            error_log('Connection failed: ' . $e->getMessage() . PHP_EOL);
            return false;
        }
    }

    public function close()
    {
        $this->connection = null;
    }
}

?>
