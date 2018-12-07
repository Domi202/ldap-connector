<?php
require_once 'vendor/autoload.php';

$connection = new \Domi\LdapConnector\Connection(
    'ldaps://ldap-server:1636',
    'uid=UserAccount,ou=admin,dc=corporate,dc=example,dc=com',
    'password'
);

$search = new \Domi\LdapConnector\Search(
    $connection,
    'ou=users,dc=corporate,dc=example,dc=com',
    '(&(objectclass=person))',
    [
        'displayname',
        'uid',
        'dn',
        'sn',
        'mail',
    ]
);

foreach ($search as $dn => $result) {
    var_dump($dn, $result);
}
