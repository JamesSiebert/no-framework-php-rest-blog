# no-framework-php-rest-blog

##  PHP REST API with no framework (blog)



**Reference**
https://youtu.be/OEWXbpUMODk
https://youtu.be/-nq4UbD0NT8
https://youtu.be/tG2U18EmIu4



### **Postman requests:**

Online Collection:
https://go.postman.co/workspace/Team-Workspace~1c849629-447e-4c41-8620-1c2fae5aafb2/collection/5025758-6a03a670-c378-41a1-a42a-e34f96cfc89a



#### Post - Get all

GET http://no-framework-php-rest-blog.local/api/post/read.php

Response:


```json
Returns:
{
    "data": [
        {
            "id": "1",
            "title": "Technology Post One",
            "body": "Lorem ipsum...",
            "author": "Sam Smith",
            "category_id": "1",
            "category_name": "Technology"
        },
        ...
```



#### Post - Get single

GET http://no-framework-php-rest-blog.local/api/post/read_single.php?id=3

Response:

```json
{
    "id": "3",
    "title": "Technology Post Two",
    "body": "Lorem ipsum...",
    "author": "Sam Smith",
    "category_id": "1",
    "category_name": "Technology"
}
```



#### Post - Create

POST 

Headers:

```json
Content-Type:application/json
```

Body:

```json
{
    "title": "My Tech Post",
    "body": "this is a sample post",
    "author": "James",
    "category_id": "1"
}
```

Response:

```json
{
    "message": "Post Created"
}
```



#### Post - Update

POST http://no-framework-php-rest-blog.local/api/post/update.php

Headers:

```json
Content-Type:application/json
```

Body:

```json
{
    "title": "Updated post",
    "body": "this is a sample post",
    "author": "James",
    "category_id": "1",
    "id": "4"
}
```

Response:

```json
{
    "message": "Post Updated"
}
```



#### Post - Delete

DELETE http://no-framework-php-rest-blog.local/api/post/delete.php

Headers:

```json
Content-Type:application/json
```

Body:

```json
{
    "id": "7"
}
```

Response:

```json
{
    "message": "Post Deleted"
}
```



------



#### Category - Get all

GET http://no-framework-php-rest-blog.local/api/category/read.php

Response:


```json
{
    "data": [
        {
            "id": "1",
            "name": "Technology"
        },
        {
            "id": "2",
            "name": "Gaming"
        },
        {
            "id": "3",
            "name": "Auto"
        },
        {
            "id": "4",
            "name": "Entertainment"
        },
        {
            "id": "5",
            "name": "Books"
        }
    ]
}
```



#### Category - Get single

GET http://no-framework-php-rest-blog.local/api/category/read_single.php?id=3

Response:

```json
{
    "id": "3",
    "title": "Technology Post Two",
    "body": "Lorem ipsum...",
    "author": "Sam Smith",
    "category_id": "1",
    "category_name": "Technology"
}
```



#### Category - Create

POST 

Headers:

```json
Content-Type:application/json
```

Body:

```json
{
    "title": "My Tech Post",
    "body": "this is a sample post",
    "author": "James",
    "category_id": "1"
}
```

Response:

```json
{
    "message": "Post Created"
}
```



#### Category - Update

POST http://no-framework-php-rest-blog.local/api/category/update.php

Headers:

```json
Content-Type:application/json
```

Body:

```json
{
    "title": "Updated post",
    "body": "this is a sample post",
    "author": "James",
    "category_id": "1",
    "id": "4"
}
```

Response:

```json
{
    "message": "Post Updated"
}
```



#### Category - Delete

DELETE http://no-framework-php-rest-blog.local/api/category/delete.php

Headers:

```json
Content-Type:application/json
```

Body:

```json
{
    "id": "7"
}
```

Response:

```json
{
    "message": "Post Deleted"
}
```

