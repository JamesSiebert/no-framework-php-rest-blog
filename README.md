# no-framework-php-rest-blog

##  PHP REST API with no framework (blog)



**Reference**
https://youtu.be/OEWXbpUMODk
https://youtu.be/-nq4UbD0NT8



### **Postman requests:**

Online Collection:
https://go.postman.co/workspace/Team-Workspace~1c849629-447e-4c41-8620-1c2fae5aafb2/collection/5025758-6a03a670-c378-41a1-a42a-e34f96cfc89a



**Get all posts**

GET http://no-framework-php-rest-blog.local/api/post/read.php


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



**Get single post**

GET http://no-framework-php-rest-blog.local/api/post/read_single.php?id=3

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

