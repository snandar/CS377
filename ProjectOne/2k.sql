/*(k)  (6 points) Which restaurant in Columbia Station has the highest average review (return the business
name and the average rating)?*/

SELECT s.name, s.average
FROM (SELECT b.name as name, AVG(r.stars) as average, b.id as id, b.city as city
	FROM business as b, review as r
	WHERE b.id = r.business_id 
	GROUP BY b.name) as s
WHERE s.id IN (SELECT business_id FROM category WHERE category LIKE '%Restaurants%')
AND s.city = 'Columbia Station'
ORDER BY s.average DESC
LIMIT 1;

