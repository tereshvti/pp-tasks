## Task 1
```
SELECT
    u.id AS 'ID',
    CONCAT(u.first_name, ' ', u.last_name) AS 'Name',
    MIN(b.author) AS 'Author',
    GROUP_CONCAT(b.name) AS 'Books'

FROM users u
LEFT JOIN user_books ub ON ub.user_id = u.id
LEFT JOIN books b ON b.id = ub.book_id

WHERE TIMESTAMPDIFF(year,u.birthday, now()) BETWEEN 7 and 17

GROUP BY u.id
HAVING COUNT(ub.id) = 2 -- all in all there are 2 books
   AND COUNT(DISTINCT b.author) = 1 -- only 1 unique author
   AND COUNT(TIMESTAMPDIFF(day, ub.get_date, ub.return_date) < 14) = 2 -- 2 books were returned in 14 days period
```

## Task 2
- Update hosts file with `phpfpm.local` pointing to localhost (127.0.0.1)
- Build image with `docker-compose build`
- Run container with `docker-compose up`
- Use Bearer token `QOIR_i3IhAt51eAZV6TCH9mLUlP7Jdolux54u4K7pMRIfO1s-LrWEdQ1NNLZKZyj` for auth
- Request examples:
1. Rates request
   ```curl --location 'http://phpfpm.local:8080/api/v1?method=rates&currency=RUB%2CEUR%2CUSD' --header 'Authorization: Bearer QOIR_i3IhAt51eAZV6TCH9mLUlP7Jdolux54u4K7pMRIfO1s-LrWEdQ1NNLZKZyj'```


2. Convert request
   ```curl --location --request POST 'http://phpfpm.local:8080/api/v1?method=convert&currency_from=USDT&currency_to=USD&value=100' --header 'Authorization: Bearer QOIR_i3IhAt51eAZV6TCH9mLUlP7Jdolux54u4K7pMRIfO1s-LrWEdQ1NNLZKZyj'```