/*(7 points) Find the University of Illinois at Urbana-Champaign students in the system. 
1) reviewed a coffee shop (has the category as ‘coffee & tea’) , 
2) a bar (has the category either ‘bars’ or ‘pubs’) and 
3) a pizza place (has the category ‘pizza’) in Champaign, IL. 
Results: 
1) all the available user information 
2) ordered in alphabetical order (by name) 
3) ties broken by join date (most recent first).*/

SELECT user.id, user.name, user.yelping_since
FROM user
WHERE id IN (SELECT user_id 
	FROM review
	WHERE business_id IN (SELECT business_id FROM category WHERE category = 'coffee & tea')
	AND business_id IN (SELECT id FROM business WHERE state='IL' AND city ='Champaign'))
AND id IN (SELECT user_id 
	FROM review
	WHERE business_id IN (SELECT business_id FROM category WHERE category = 'bars' OR category = 'pubs')
	AND business_id IN (SELECT id FROM business WHERE state='IL' AND city ='Champaign'))
AND id IN (SELECT user_id 
	FROM review
	WHERE business_id IN (SELECT business_id FROM category WHERE category = 'pizza')
	AND business_id IN (SELECT id FROM business WHERE state='IL' AND city ='Champaign'))
ORDER BY user.name ASC, user.yelping_since DESC;
