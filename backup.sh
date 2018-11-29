echo mysql data backup !!
/usr/local/mysql/bin/mysqldump -uroot -proot0922 mtns --lock-tables=false > /home/mtnsbak/sql_bak/mtns.sql
/usr/local/mysql/bin/mysqldump -uroot -proot0922 realdb --lock-tables=false > /home/mtnsbak/sql_bak/realdb.sql
/usr/local/mysql/bin/mysqldump -uroot -proot0922 mtns_kofic  --lock-tables=false > /home/mtnsbak/sql_bak/mtns_kofic.sql

echo cd
cd /home/mtnsbak/sql_bak/

echo tar compress!!
tar cvfz /home/mtnsbak/mtns.tar.gz /home/mtnsbak/sql_bak/mtns.sql
tar cvfz /home/mtnsbak/realdb.tar.gz /home/mtnsbak/sql_bak/realdb.sql
tar cvfz /home/mtnsbak/mtns_kofic.tar.gz /home/mtnsbak/sql_bak/mtns_kofic.sql

echo mysql shutdown
/usr/local/mysql/bin/mysqladmin -uroot -proot0922 shutdown

echo mysql zipping......
echo zip -r /home/mtnsbak/amfphp_gest.zip /home/local/mysql/var_old/amfphp_gest/*
echo zip -r /home/mtnsbak/anypos.zip /home/local/mysql/var_old/anypos/*
echo zip -r /home/mtnsbak/movebox.zip /home/local/mysql/var_old/movebox/*
echo zip -r /home/mtnsbak/mt.zip /home/local/mysql/var_old/mt/*
zip -r /home/mtnsbak/mtns.zip /home/local/mysql/var_old/mtns/*
zip -r /home/mtnsbak/mtnsback.zip /home/local/mysql/var_old/mtnsback/*
zip -r /home/mtnsbak/mtns_bak.zip /home/local/mysql/var_old/mtns_bak/*
zip -r /home/mtnsbak/mtns_kofic.zip /home/local/mysql/var_old/mtns_kofic/*
echo zip -r /home/mtnsbak/mysql.zip /home/local/mysql/var_old/mysql/*
echo zip -r /home/mtnsbak/nmail2.zip /home/local/mysql/var_old/nmail2/*
echo zip -r /home/mtnsbak/pos_001.zip /home/local/mysql/var_old/pos_001/*
echo zip -r /home/mtnsbak/realdb.zip /home/local/mysql/var_old/realdb/*
echo zip -r /home/mtnsbak/realtimebox.zip /home/local/mysql/var_old/realtimebox/*
echo zip -r /home/mtnsbak/restpos_000.zip /home/local/mysql/var_old/restpos_000/*
echo zip -r /home/mtnsbak/tattertools.zip /home/local/mysql/var_old/tattertools/*
echo zip -r /home/mtnsbak/test.zip /home/local/mysql/var_old/test/*
echo zip -r /home/mtnsbak/zeroboard.zip /home/local/mysql/var_old/zeroboard/*

echo mysql restart
/usr/local/mysql/bin/mysqld_safe --user=mysql & apachectl restart

echo time set !!
rdate -s time.bora.net && clock -w

