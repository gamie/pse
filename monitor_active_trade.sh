export MYSQL_PWD=pse
while true ; do clear ; mysql -u pse pse --table < active_trade_monitoring.sql ; sleep 60 ; done
