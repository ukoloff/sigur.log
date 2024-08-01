with recursive Deps as(
  select
    ID,
    PARENT_ID,
    NAME
  from
    personal
  where
    `TYPE` = 'DEP'
    and STATUS = 'AVAILABLE'
),
roots as (
  select
    D.ID
  from
    Deps D
    left join Deps Z on D.PARENT_ID = Z.ID
  where
    Z.ID is NULL
),
tree as (
  select
    X.ID as p_id,
    1 as p_level,
    Z.ID as c_id,
    2 as c_level
  from
    roots X
    join Deps Z on X.ID = Z.parent_id
  union all
  select
    T.p_id,
    T.p_level,
    D.ID,
    T.c_level + 1
  from
    tree as T
    join Deps as D on T.c_id = D.PARENT_ID
  union all
  select
    T.c_id,
    T.c_level,
    D.ID,
    T.c_level + 1
  from
    tree as T
    join Deps as D on T.c_id = D.PARENT_ID
),
ccounts as (
  select
    p_id as id,
    count(*) as n
  from
    tree
  group by
    p_id
)
select
  tree.*,
  ccounts.n
from
  Tree
  join ccounts on p_id = id
order by
  p_id,
  c_level
