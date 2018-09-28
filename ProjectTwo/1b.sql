/*(5 points) 

Cond: users who have been to all three states (IL, OH, WI). 
Result: user.id, name, avg(stars), total number of reviews, number of cities
Order by the number of cities (decreasing order) 
break ties with the average stars (decreasing)

*/

SELECT s.uID, s.uName, AVG(stars) as avgStars, COUNT(review.id) as countReview, COUNT(DISTINCT business.city) as countCity
FROM (SELECT user_id as uID, user.name as uName
	FROM review, business, user
	WHERE review.business_id = business.id 
	AND user.id = user_id
	AND state IN ('IL', 'WI', 'OH')
	GROUP BY user_id
	HAVING COUNT(DISTINCT state) = '3') as s, review, business
WHERE review.user_id = s.uID
AND review.business_id = business.id
GROUP BY s.uID
ORDER BY countCity DESC, avgStars DESC;

