/*What is all the information for the top 5 most useful reviews (based on votes)*/

select*  FROM review ORDER BY useful DESC LIMIT 5;
