/*How many bakeries are in Champaign, IL?*/

SELECT count(business_id) as bakeries_count
FROM category
WHERE business_id IN(SELECT id FROM business WHERE state='IL' AND city='Champaign')
AND category='bakeries';
