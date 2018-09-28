/*(10 points) Identify businesses that received more than 50 5-star reviews from the “fake accounts” (see (c)). 
Your results should return the business id, name, city, state, and the percentage of such “fake” reviews 
ordered by the percentage in decreasing order. 
Hint: You may find using a SELECT subquery within a SELECT part of the outer query useful to calculate the percentage.*/

SELECT s.business_id, business.name, business.city, business.state, (SELECT (COUNT(s.id)/COUNT(id))*100 FROM review WHERE s.business_id = review.business_id) as percentage
FROM (SELECT business_id, id, stars FROM review GROUP BY user_id HAVING COUNT(id)='1') as s, business
WHERE business.id = s.business_id
AND s.stars = '5'
GROUP BY s.business_id
HAVING COUNT(s.id) > 50
ORDER BY percentage DESC;
