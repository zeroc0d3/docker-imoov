<?php

$hostname='database';
$username='admin';
$password='password';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=dbname",$username,$password);

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to Database<br/>';

    $dbh = null;
} catch(\PDOException $e) {
    echo $e->getMessage();
}
