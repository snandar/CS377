/*5 points) What business (name, address, city, state) got the last 1 star review (most recent based on
the date of the review)?*/

SELECT name, address, city, state 
FROM business 
WHERE id IN (SELECT business_id FROM review WHERE date = (SELECT MAX(date) FROM review) AND	stars = 1)
LIMIT 1;

