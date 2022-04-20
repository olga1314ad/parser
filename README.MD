ЗАПУСКАЙТЕ ИЗ КОНСОЛИ  
bin/console app:parse


For CRUD

Headers :
Content-Type : application/ld+json
Accept : application/ld+json
______________________________
Categories:
GET 
http://localhost:85/api/categories

POST
http://localhost:85/api/categories
{
"id": {id},
"title": "string",
"parent": "api/categories/{parent_id}"
}

GET, DELETE
http://localhost:85/api/categories/{id}

PUT, PATCH
http://localhost:85/api/categories/{id}

{
"id" : {id},
"title": "string",
"parent": "api/categories/{parent_id}"
}
__________________________________
Offer
