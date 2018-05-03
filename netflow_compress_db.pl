#!/usr/local/bin/perl
 
use DBI;
use Time::localtime;
use POSIX ":sys_wait_h";
 
sub round
{
  $float = shift; # the number to round
  $intgr = int($float + 0.5);
  return $intgr;
}
 
$gm = localtime();
$year = ($gm->year()) + 1900;
$mounth = ($gm->mon());
$mday = $gm->mday();
$date = "$mday-$mounth-$year";
$table_date = "$year\_$mounth";
$table_date_compress = "$table_date"."c";
 
$dbh = DBI->connect("DBI:mysql:host=localhost;database=netflow","root","rootPASS") or die "Not MySQL Access!";
 
$repair = "REPAIR TABLE $table_date";
$sth = $dbh->prepare("$repair");
$sth->execute();
 
$alter1 = "ALTER TABLE $table_date RENAME $table_date_compress";
$sth = $dbh->prepare("$alter1");
$sth->execute();
 
$flush1 = "FLUSH TABLE $table_date_compress";
$sth = $dbh->prepare("$flush1");
$sth->execute();
 
`/usr/local/bin/myisamchk /var/db/mysql/netflow/$table_date_compress`;
`/usr/local/bin/myisampack /var/db/mysql/netflow/$table_date_compress`;
`/usr/local/bin/myisamchk -rq --sort-index --analyze /var/db/mysql/netflow/$table_date_compress`;

$flush2 = "FLUSH TABLE $table_date_compress";
$sth = $dbh->prepare("$flush2");
$sth->execute();
 
$sth->finish;
$dbh->disconnect;