export MYSQL_PWD=pse
while true ; do clear ; mysql -u pse pse --table < support_monitoring.sql ; sleep 60 ; done
