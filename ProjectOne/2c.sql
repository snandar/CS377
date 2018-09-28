/*(4 points) List all the businesses (name, address, neighborhood) in Cleveland, OH who have ‘coffee’ in
its name.*/

SELECT name, address, neighborhood
FROM business 
WHERE city='Cleveland' 
AND name LIKE '%Coffee%'
AND state = 'OH';
