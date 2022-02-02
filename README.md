# no-framework-php-rest-blog

##  PHP REST API with no framework (blog)

My GitHub repo: https://github.com/JamesSiebert/no-framework-php-rest-blog

Portfolio project link: 

**Tutorial Reference**
https://youtu.be/OEWXbpUMODk
https://youtu.be/-nq4UbD0NT8
https://youtu.be/tG2U18EmIu4

Note: Tutorial includes all of Posts setup then provides the steps to implement Categories.



## **REQUESTS:**

Postman Collection:
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
    "name": "Auto"
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
    "name": "PHP"
}
```

Response:

```json
{
    "message": "Category Created"
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
    "name": "Updated category",
    "id": "4"
}
```

Response:

```json
{
    "message": "Category Updated"
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
    "id": "3"
}
```

Response:

```json
{
    "message": "Category Deleted"
}
```





## Deployment Options

**Notes:** 

.htaccess - Blocks access to folder views
config/.env is for DB secrets

#### cPanel Installation

1. Create subdomain
2. Link subdomain directory to GitHub & pull
3. Create DB
4. Seed DB - Import  migration.sql
5. Create DB User - LIMITED ACCESS
6. Copy config/.envExample to the same directory and name .env
7. Fill in the DB values
8. cPanel Terminal: cd into project root then 'composer install'
9. Test by calling an end point, if there are issues visit the page in your browser to see the message.



#### AWS LightSail

Reference: https://aws.amazon.com/getting-started/hands-on/launch-lamp-web-app/

Create new LAMP instance

Launch Script

```
# remove default website
#-----------------------
cd /opt/bitnami/apache2/htdocs 
rm -rf *

# clone github repo
#------------------
git clone -b loft https://github.com/JamesSiebert/no-framework-php-rest-blog .

# set write permissons on the settings file
#-----------------------------------
chown bitnami:daemon connectvalues.php
chmod 666 connectvalues.php

# inject database password into configuration file
#-------------------------------------------------
sed -i.bak "s/<password>/$(cat /home/bitnami/bitnami_application_password)/;" /opt/bitnami/apache2/htdocs/connectvalues.php

# create database
#----------------
cat /home/bitnami/htdocs/data/migration.sql | /opt/bitnami/mysql/bin/mysql -u root -p$(cat /home/bitnami/bitnami_application_password)
```

Select cheapest - 512 MB RAM, 1 vCPU, 20 GB SSD
Name: LAMP-no-framework-php-rest-blog-1

Note: to get password 'cat bitnami_application_password'

Generate Certificate - sudo /opt/bitnami/bncert-tool
