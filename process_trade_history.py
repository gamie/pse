#!/usr/bin/python

import csv

with open('trade_history.txt') as input:
	print zip(*(line.strip().split('\t') for line in input))

