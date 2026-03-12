<?php

$host = 'dpg-d6p31dh4tr6s73af2opg-a.singapore-postgres.render.com';
$port=5432;
$dbname = 'store_0yeg';
$user = 'store_0yeg_user';
$password = 'bNV2bCc3iRIEh5F76AW4oi5PfgpbtCVz';

$conn = pg_connect( "host=$host port=$port dbname=$dbname user=$user password=$password" );

if ( !$conn ) {
    die( 'Database connection failed.' );
}

?>