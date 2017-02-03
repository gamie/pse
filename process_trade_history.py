#!/usr/bin/python

import csv
import MySQLdb
import datetime

# Open database connection
db = MySQLdb.connect("127.0.0.1","pse","pse","pse" )

# prepare a cursor object using cursor() method
cursor = db.cursor()

with open('../data/trade_history.txt','r') as f:
    reader = csv.reader(f,delimiter='\t')
    for number,trx_number,order_number,order_date,symbol,shares_qty,match_shares,price,trxtype,status in reader:
        if number and (number != '#'):
            shares_qty = shares_qty.replace(",","")
            match_shares = match_shares.replace(",","")
            price = price.replace(",","")
            order_date = datetime.datetime.strptime(order_date,'%m/%d/%Y  %H:%M:%S')
            order_date = datetime.datetime.strftime(order_date,'%Y-%m-%d %H:%M:%S')
            str = "insert into trade_history values ('"  + trx_number + "','"  + order_number + "','"  + order_date + "','"  + symbol \
                + "','"  + shares_qty + "','"  + match_shares + "','"  + price + "','"  + trxtype + "','"  + status + "')"
            # try to insert into DB
            try:
                cursor.execute(str)
            except MySQLdb.Error, e:
                try:
                    print datetime.datetime.now().strftime("%Y-%m-%d %H:%M") + "|MySQL Error [%d]: %s" % (e.args[0], e.args[1])
                except IndexError:
                    print datetime.datetime.now().strftime("%Y-%m-%d %H:%M") + "|MySQL error: %s" % str(e)

# commit and disconnect from server
db.commit()
db.close()

