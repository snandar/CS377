/*(5  points)  Identify  the  top  10  businesses  in  Ohio  that  have  the  highest  star  average  and  have  been
reviewed at least 20 times.  Results should contain the business id, the name, the average star ratings,
and the total number of reviews and be ordered by the ratings in descending order.*/

SELECT business.id, business.name, AVG(review.stars), count(review.id)
FROM business, review
WHERE business.id = review.business_id
AND business.state = 'OH'
GROUP BY business_id
HAVING count(review.id) > 19
ORDER BY AVG(review.stars) DESC
LIMIT 10;
