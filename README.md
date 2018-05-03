# netflowstats
NetFlow v5 Statictics Scripts

This project can be used to capture, parse and load NetFlow statictics from 
any NetFlow v5 compatibility Device (Like Mikrotik and etc) to MySQL Database.
Next with PHP UI you can easy parse SQL DB per month statictics and view some queryes.


Example: Fast Start use scripts with FreeBSD + MySQL + Mikrotik NetFlow v5:

**On Mikrotik:**

/ip traffic-flow
  
set enabled=yes interfaces=WAN
  
/ip traffic-flow target
  
add dst-address=X.X.X.X port=8787 v9-template-timeout=1m version=5



**On FreeBSD:**

pkg install flow-tools
  
  echo 'flow_capture_enable="YES"' >> /etc/rc.conf.local
  
  echo 'flow_capture_flags="-N-2"' >> /etc/rc.conf.local
  
  service flow_capture start
  
  pkg install mysql56-server
  
  echo 'mysql_enable="YES"' >> /etc/rc.conf
  
  service mysql-server start
  
  mysql_secure_installation
  
  pkg install p5-DBI p5-DBD-mysql
  
mysql -u root -p


**On MySQL:**

mysql> create database netflow;

mysql> grant insert,create,update,select,delete on netflow.* to nfuser@'localhost' identified by '987654321';

mysql> flush privileges;

mysql> exit;


Script usage:

**netflow.pl** - near end of a day (like 23:50). Script parse all ft-* files from flow-capture.

**netflow.php** - use it with WebServer + PHP 5.x, 7.x + phpX-mysqli.

**netflow_compress_db.pl** - use it on first day in a new mounth to compress last mounth and sort index (to make future SQL-queue more fast).

## PHP UI Examples ##

![Start1](https://github.com/Dorlas/netflowstats/blob/master/php_screens/Start1.png)

![Start2](https://github.com/Dorlas/netflowstats/blob/master/php_screens/Start2.png)

![Start3](https://github.com/Dorlas/netflowstats/blob/master/php_screens/Start3.png)

![Start4](https://github.com/Dorlas/netflowstats/blob/master/php_screens/Start4.png)

![Start5](https://github.com/Dorlas/netflowstats/blob/master/php_screens/Start5.png)

