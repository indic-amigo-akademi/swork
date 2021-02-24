<?php
class Auth{
    private $_ROLES;
    public function __construct($users=['ADMIN', 'USER', 'GUEST'])
    {
        $this->_ROLES = $users;
    }
}
?>