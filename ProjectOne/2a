/*Which user (id,name) has been yelping the longest? if tie, return user based on alphabetical order*/

SELECT id,name
FROM user 
WHERE yelping_since = (SELECT MIN(yelping_since) 
					FROM user) 
ORDER BY name ASC
LIMIT 1;
