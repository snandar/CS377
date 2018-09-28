/*  (6 points) Which user (id) has reviewed the most businesses in University Heights and how many did he/she review? 
 You will want to deal with abbreviations and do not use the postal code */

SELECT s.userID, s.count
FROM (SELECT user_id as userID, COUNT(review.id) as count, business_id as bID 
	FROM review, business
	WHERE business.id=review.business_id
	AND city LIKE '%uni%'
	GROUP BY user_id) as s
ORDER BY s.count DESC
LIMIT 1;
