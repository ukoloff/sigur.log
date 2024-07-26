select
	U.ID,
	U.NAME,
	(
		select
			count(*)
		from
			reportuserdep r
			join personal D on r.EMP_ID = D.ID
			and D.`TYPE` = 'DEP'
			and D.STATUS = 'AVAILABLE'
		where
			r.USER_ID = U.ID
	) as N,
	U.EXTID,
	U.AD_USER_DN
from
	personal U
where
	U.USER_ENABLED
group by
	U.ID
order by
	N desc
