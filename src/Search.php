<?php

namespace Domi\LdapConnector;

class Search implements \Iterator, \Countable
{
    /**
     * @var resource
     */
    private $connection;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var string
     */
    private $baseDn;

    /**
     * @var string
     */
    private $filter;

    /**
     * @var resource
     */
    private $search;

    /**
     * @var resource
     */
    protected $currentEntry;

    /**
     * Search constructor
     * @param Connection $connection
     * @param string $baseDn
     * @param string $filter
     * @param array $attributes
     */
    public function __construct(
        Connection $connection,
        string $baseDn,
        string $filter,
        $attributes = ['dn']
    ) {
        $this->connection = $connection->getConnection();
        $this->baseDn = $baseDn;
        $this->filter = $filter;
        $this->attributes = $attributes;

        $this->search = ldap_search(
            $this->connection,
            $this->baseDn,
            $this->filter,
            $this->attributes,
            null,
            null,
            300
        );

        $this->currentEntry = ldap_first_entry($this->connection, $this->search);
    }

    public function current(): array
    {
        return ldap_get_attributes($this->connection, $this->currentEntry);
    }

    public function next()
    {
        $this->currentEntry = ldap_next_entry($this->connection, $this->currentEntry);
    }

    public function key()
    {
        return ldap_get_dn($this->connection, $this->currentEntry);
    }

    public function valid(): bool
    {
        return is_resource($this->currentEntry);
    }

    /**
     * Rewind the Iterator to the first element
     * @return void
     */
    public function rewind()
    {
        $this->currentEntry = ldap_first_entry($this->connection, $this->search);
    }

    /**
     * Count elements of an object
     * @return int The custom count as an integer.
     */
    public function count(): int
    {
        $count = ldap_count_entries($this->connection, $this->search);
        if ($count !== false) {
            return $count;
        }
        return 0;
    }
}