#!/usr/bin/python

import csv

number=[]
trx=[]
order=[]
order_date=[]
stkcode=[]
enuofshares=[]
matchedshares=[]
price=[]
trxtype=[]
status=[]

with open('trade_history.txt','r') as f:
    next(f) # skip headings
    reader = csv.reader(f,delimiter='\t')
    for number,trx,order,order_date,stkcode,enuofshares,matchedshares,price,trxtype,status in reader:
        number.append(number)
        trx.append(trx)
        order.append(order)
        order_date.append(order_date)
        stkcode.append(stkcode)
        enuofshares.append(enuofshares)
        matchedshares.append(matchedshares)
        price.append(price)
        trxtype.append(trxtype)
        status.append(status)

print (trx)
print (stkcode)
print (matchedshares)
print (price)
print (trxtype)

