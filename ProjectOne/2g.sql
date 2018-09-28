/*How many parks are in Ohio (OH)?*/

select count(business_id) 
FROM category 
WHERE business_id IN (SELECT id
					  FROM	 business
					  WHERE  state = 'OH')
AND category ='parks';
