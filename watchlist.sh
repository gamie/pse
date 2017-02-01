#!/bin/sh

php fetch_json_ticker.php
clear
uptime
mysql -u pse pse -ppse -e "select symbol, amount, percent_change, volume from stockticker where symbol in ('2GO','ALI','BDO','CNPF','CPG','FLI','GLO','JFC','MER','MWIDE','NI','PGOLD','PXP','SECB','X','CHP') order by percent_change desc;"
