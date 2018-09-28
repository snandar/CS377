/*4 points) What are the names and addresses of all businesses in the Dudgeon-Monroe neighborhood in
Madison, WI?*/

SELECT name, address
FROM business 
WHERE neighborhood ='Dudgeon-Monroe'
AND city='Madison'
AND state='WI';
