/*d) (5 points) Write the SQL query to find out which country received the most gold medals (assume no
ties)?*/

SELECT s.country
FROM (SELECT country, count(medals) as count
				FROM Athlete, Medals 
				WHERE Athlete.aID = Medals.aID
				AND Medals.medals = 'First'
				GROUP BY country) as s
ORDER BY s.count DESC
LIMIT 1;
