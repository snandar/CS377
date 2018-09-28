/*5  points)  How  many  reviews  have  been  written after January  1st,  2017  for  a  business  in  Wisconsin(WI)?*/

SELECT count(id)
FROM review
WHERE business_id IN (SELECT id FROM business WHERE state='WI')
AND date > '2017-01-01';
