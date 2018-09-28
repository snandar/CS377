/*(6 points) In Ogden (IL), how many 5-star reviews does each category of business have?  Order your
results based on the number of reviews in descending order.*/

SELECT count(review.id) as count, category.category
FROM category, review, business
WHERE category.business_id=review.business_id
AND business.id = category.business_id
AND review.stars = '5'
AND business.city = 'Ogden' AND state ='IL'
GROUP BY category.category
ORDER BY count DESC;
