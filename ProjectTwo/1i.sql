/*(10 points) Result: (id, name, yelping since, and number of reviews written) 
Condition: only reviewed businesses in Lakewood, OH 
			reviewed at least 2 businesses in Lakewood, OH. 
Order By; number of reviews (largest is first) and 
		ties broken by the yelping date (longest time on yelp first).*/

SELECT user.id, user.name, user.yelping_since, s.count
FROM ((SELECT user_id, COUNT(review.id) as count
	FROM review, business
	WHERE review.business_id = business.id
	AND city ='Lakewood' AND state ='OH'
	GROUP BY user_id
	HAVING count > 1)) as s, user 
WHERE s.user_id = user.id
GROUP BY user.id
HAVING s.count = (SELECT COUNT(id) FROM review WHERE user.id = review.user_id)
ORDER BY s.count DESC, user.yelping_since ASC;
