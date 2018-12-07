<?php

namespace Domi\LdapConnector;

class Connection
{
    /**
     * @var resource
     */
    private $connection;

    /**
     * @var string
     */
    private $connectionString;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    public function __construct(
        string $connectionString,
        string $user,
        string $password
    ) {
        $this->connectionString = $connectionString;
        $this->user = $user;
        $this->password = $password;

        $this->initializeConnection();
    }

    protected function initializeConnection()
    {
        ldap_set_option(null, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option(null, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);

        $this->connection = ldap_connect($this->connectionString);
        ldap_bind($this->connection, $this->user, $this->password);
    }

    /**
     * @return resource
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function __destruct()
    {
        if (is_resource($this->connection)) {
            ldap_close($this->connection);
        }
    }
}