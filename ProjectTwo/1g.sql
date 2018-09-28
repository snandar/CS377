/*(8 points) 1) businesses that have more than 10 reviews and 
			 2) all of them are “5 stars”. 
Result: print the id, name, city, state, and number of reviews.
Order by the number of reviews received (decreasing), and then business ids (increasing). 
*/

SELECT business.id, business.name, business.city, business.state, COUNT(review.id)
FROM business, review
WHERE business.id = review.business_id
AND business.id IN (SELECT business_id FROM review WHERE stars = '5')
AND business.id NOT IN (SELECT business_id FROM review WHERE stars < '5')
GROUP BY business.id
HAVING COUNT(review.id) > 10
ORDER BY COUNT(review.id) DESC, business.id ASC;
