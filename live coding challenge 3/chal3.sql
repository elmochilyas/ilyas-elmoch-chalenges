SELECT  title, author, price
FROM library_books;

SELECT * FROM library_books WHERE price<300 and price>100 ;

SELECT * FROM library_books WHEREpublished_year > 2020  ;

SELECT * FROM library_books
WHERE title LIKE '%PHP%';

SELECT * FROM library_books WHERE status != 'Lost' ORDER BY published_year ;

SELECT DISTINCT author
FROM library_books;

SELECT 
    UPPER(title) AS formatted_title,
    ROUND(price) AS rounded_price
FROM library_books;

