/*(10 points) 

Cond: only reviews users who have been to more than 10 cities, 
list the top 10 burger restaurants 
(have one category as ‘burgers’) 
where they have more than 2 traveler reviews 
and have the highest average star rating from the travelers.

Order By: score (highest first) 
break ties with the number of reviews by the travelers (decreasing)
then business name. 

Result: business id, name, city, state, average stars, and number of traveler reviews received.*/

SELECT business.id, business.name, business.city, business.state, AVG(review.stars), COUNT(review.id)
FROM (SELECT user_id as uID
	FROM review, business
	WHERE review.business_id = business.id 
	GROUP BY user_id
	HAVING COUNT(DISTINCT city) > 10) as s, business, review
WHERE review.user_id = s.uID
AND business.id = review.business_id
AND business.id IN (SELECT business_id FROM category WHERE category='burgers')
GROUP BY business.id
HAVING COUNT(review.id) > 2
ORDER BY AVG(stars) DESC, COUNT(review.id) DESC, business.name ASC
LIMIT 10; 
