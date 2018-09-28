/*(5 points) How many 3 star and above reviews did Caribou Coffee (multiple stores may have this name,
so they should all be included) receive?*/

SELECT	count(stars)
FROM 	review
WHERE	business_id IN (SELECT id
						FROM business
						WHERE name LIKE '%Caribou Coffee%')
AND 	stars>2; 
