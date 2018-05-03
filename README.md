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
add dst-address=<NAS IP Address> port=8787 v9-template-timeout=1m version=5
</code>



