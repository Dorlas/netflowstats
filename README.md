# netflowstats
NetFlow v5 Statictics Scripts

This project can be used to capture, parse and load NetFlow statictics from 
any NetFlow v5 compatibility Device (Like Mikrotik and etc) to MySQL Database.
Next with PHP UI you can easy parse SQL DB per month statictics and view some queryes.

Example: Fast Start use scripts with FreeBSD + MySQL + Mikrotik NetFlow v5:

On Mikrotik:

<code>
  /ip traffic-flow
  set enabled=yes interfaces=WAN
  
  /ip traffic-flow target
  add dst-address=X.X.X.X port=8787 v9-template-timeout=1m version=5
</code>

On FreeBSD:

<code>
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
</code>

On MySQL:

<code>
  mysql> create database netflow;
  mysql> grant insert,create,update,select,delete on netflow.* to nfuser@'localhost' identified by '987654321';
  mysql> flush privileges;
  mysql> exit;
</code>


  
