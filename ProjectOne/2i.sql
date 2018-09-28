/*5 points) What are the reviews for Prairie Cafe & Bakery sorted by the business id and then the number
of funny votes (lowest to highest)?  Your query should return the business id (there may be multiple
businesses), the user id, the text, and the funny votes*/

SELECT  business_id, user_id, text, funny
FROM 	review
WHERE 	business_id IN (SELECT 	id
						FROM 	business
						WHERE	name = 'Prairie Cafe & Bakery')
ORDER BY business_id, funny ASC; 
