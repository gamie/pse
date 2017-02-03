#!/bin/sh

echo "Delete last data..."
/bin/rm /Users/gamie/Work/data/stockinfo/*
echo "Fetching current data..."
/Users/gamie/Work/pse/fetch_stockinfo.sh >> ../log/fetch_stockinfo.log  
echo "Processing current data..."
/Users/gamie/Work/pse/process_stockinfo.sh >> ../log/process_stockinfo.log
echo "Completed loading current data..."
