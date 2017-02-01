/* Monitoring price near support1 level under watch list */

select
   t1.symbol,
   t1.amount as price,
   t2.support1 as support,
   t1.percent_change as per_change,
   format(((t1.amount - t2.support1) / t2.support1) * 100, 2) as percent
   from
      stockticker t1,
      technical_summary t2
   where
      t1.symbol = t2.symbol
      and t1.amount <= t2.support1 * 1.02
      and t2.recdate = (select recdate from technical_summary where symbol = t1.symbol order by recdate desc limit 1)
      and t1.symbol
      in
         (select symbol from watchlist) order by percent;


/* Monitoring price near support1 level not under watch list */
select
   t1.symbol,
   t1.amount as price,
   t2.support1 as support,
   t1.percent_change as per_change,
   format(((t1.amount - t2.support1) / t2.support1) * 100, 2) as percent
   from
      stockticker t1,
      technical_summary t2
   where
      t1.symbol = t2.symbol
      and t1.amount <= t2.support1 * 1.01
      and t2.recdate = (select recdate from technical_summary where symbol = t1.symbol order by recdate desc limit 1)
      and t1.symbol
      not in
         (select symbol from watchlist) order by percent;
