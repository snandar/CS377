/*(6 points) What users (all their information) have written more than 150 reviews?*/

SELECT user.id, user.name, user.yelping_since
FROM user, review
WHERE user.id = review.user_id
GROUP BY user_id
HAVING Count(review.id)>150;
