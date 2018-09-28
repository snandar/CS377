/*(14 points) 
Requirement: 
businesses average rating jumped by at least 1 star from March 2017 to April 2017
(based only on that month’s reviews with at least 3 reviews for that month) 

Result: 
1) each business, id, name, city, 
2) jump
3) number of reviews in March, # in April. 

Order: 
1) magnitude of the jump (largest first)
2) number of reviews in April (largest first)
3) business names (increasing). 

Hint:
MONTH, YEAR functions useful to extract the month and year from the date of the review.*/

SELECT a.business_id, business.name, business.city, (avgApril-avgMarch) as jump, m.countMarchReview, a.countAprilReview
FROM (SELECT business_id, AVG(stars) as avgMarch, COUNT(review.id) as countMarchReview 
	FROM review 
	WHERE MONTH(date) = '3' 
	AND YEAR(date) = '2017'
	GROUP BY business_id 
	HAVING COUNT(id) > 2) as m, 
(SELECT business_id, AVG(stars) as avgApril, COUNT(review.id) as countAprilReview 
	FROM review 
	WHERE MONTH(date) = '4' 
	AND YEAR(date) = '2017'
	GROUP BY business_id 
	HAVING COUNT(id) > 2) as a, business
WHERE a.business_id = m.business_id
AND a.business_id = business.id
AND (avgApril-avgMarch) >= 1
ORDER BY jump DESC, a.countAprilReview DESC, business.name ASC;
