select 
   t1.symbol,t1.average_price,
   t2.amount as last_price, 
   format(((t2.amount-t1.average_price) / t2.amount) * 100,2) as ap_vs_lp,
	t2.percent_change,
   t1.target_price,
   format(((t2.amount-t1.target_price) / t2.amount) * 100,2) as tp_vs_lp,
   t3.resistance1 as resistance,
   format(((t2.amount-t3.resistance1) / t2.amount) * 100,2) as rp_vs_lp
from 
   active_trade t1, stockticker t2, technical_summary t3
where 
   t1.symbol = t2.symbol
   and t2.symbol = t3.symbol
   and t3.recdate = (select recdate from technical_summary where symbol = t1.symbol order by recdate desc limit 1)
order by 
   ap_vs_lp+0 desc;
