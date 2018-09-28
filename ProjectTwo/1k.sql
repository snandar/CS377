/*users who have been to all the Ramen places (has the category Ramen) in Madison, WI. 
SELECT user id, the name, yelping since 
ordered by the user id (increasing).*/

SELECT user_id, user.name, user.yelping_since
FROM review, user
WHERE review.user_id = user.id
AND business_id IN (SELECT id FROM business WHERE state = 'WI' AND city ='Madison')
AND business_id IN (SELECT business_id FROM category WHERE category = 'Ramen')
GROUP BY user_id
HAVING COUNT(DISTINCT business_id) >= ALL(SELECT COUNT(id) FROM business WHERE id IN (SELECT business_id FROM category WHERE category = 'Ramen') AND state ='WI' AND city = 'Madison')
ORDER BY user_id ASC;

