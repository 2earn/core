select *
from states
where country_id in (select id from countries where phonecode = ?)
