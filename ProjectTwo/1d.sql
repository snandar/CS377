/*(7 points) Identify the users who have traveled to Illinois, reviewed at least 5 businesses, and have not
reviewed any restaurants in the database.*/

-- SELECT user_id
-- FROM review
-- WHERE user_id NOT IN (SELECT user_id from review, category WHERE category.business_id = review.business_id AND category = 'restaurants')
-- AND user_id IN (SELECT user_id FROM business, review WHERE business.id = review.business_id AND state='IL')
-- GROUP BY user_id
-- HAVING COUNT(review.id) > 4;

SELECT s.uID
FROM (SELECT review.user_id as uID, count(business_id) as count FROM review GROUP BY user_id) as s
WHERE s.uID NOT IN (SELECT user_id from review, category WHERE category.business_id = review.business_id AND category = 'restaurants')
AND s.uID IN (SELECT user_id FROM business, review WHERE business.id = review.business_id AND state='IL')
AND s.count > 4;

