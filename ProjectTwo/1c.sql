/*(5 points) Identify the users that might be fake accounts.  Fake accounts are defined as users that have
only reviewed once and have issued a 5-star rating.  List the user id, user name, and the business id that
received the 5-star review and sort by the business id (increasing).*/



SELECT s.user_id, user.name, s.business_id
FROM (SELECT user_id, business_id, stars FROM review GROUP BY user_id HAVING COUNT(id)='1') as s, user
WHERE user.id = s.user_id
AND s.stars = '5'
ORDER BY s.business_id ASC;
