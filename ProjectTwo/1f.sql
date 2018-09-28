/*(8 points) You are visiting the Computer Science department at the University of Wisconsin, Madison 
Find the top 3 best reviewed coffee shop 
(has the category ‘coffee & tea’) 
that are within a 1.5 mile radius of the building. 

Note that the latitude and longitude of the building is (43.071293, −89.406318) 
   ( 3959 * acos( cos( radians(43.071293) )
           * cos( radians(latitude) )
           * cos( radians(longitude) - radians(-89.406318) )
           + sin( radians(43.071293) )
           * sin( radians(latitude) ) ) )
Result: business name, address, distance from the building, average number of stars, 
Order: sorted by average number of stars (decreasing) 
break ties using distance (closer is better).
*/

SELECT business.name, business.address, 
(SELECT ( 3959 * acos( cos( radians(43.071293) )
           * cos( radians(b.latitude) )
           * cos( radians(b.longitude) - radians(-89.406318) )
           + sin( radians(43.071293) )
           * sin( radians(b.latitude) ) ) ) 
FROM business b WHERE b.id = review.business_id) as distance, AVG(stars)
FROM business, review
WHERE business.id = business_id
AND state = 'WI' AND city = 'Madison'
AND  ( 3959 * acos( cos( radians(43.071293) )
           * cos( radians(latitude) )
           * cos( radians(longitude) - radians(-89.406318) )
           + sin( radians(43.071293) )
           * sin( radians(latitude) ) ) ) <= 1.5
AND business.id IN (SELECT business_id FROM category WHERE category = 'coffee & tea')
GROUP BY business_id
ORDER BY AVG(review.stars) DESC, distance ASC
LIMIT 3;
