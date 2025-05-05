## CodeIgniter 3 REST API Integration with JWT
This toolkit is for people who like to build web REST API's with token based structure like JWT using PHP. Its goal is to enable you to develop api much faster than you could if you were writing code from scratch, by providing a template for your workings with the REST API with JWT based tokens.

*********************
**Release Information**
*********************
This Projects uses two separate codeigniter framework 1. Backend api for frontend 2. Admin section to manage contents 

API folder contains admin folder for separate admin section codeigniter 
Admin folder contains separate read me file belong to backend Admin section 
*********



*********
**Features**
*********

1. Complete REST API control
2. JWT based access tokens
3. CRUD operations
4. Register/Login/Logout Mechanism
5. Proper Authentication
6. Validation control
7. DB structure given
8. Required SQL given
9. Routing handled
10. Session management
11. Postman collection added

***********
**Instruction**
***********

- Change `jwt_key` & `token_expire_time` according to your need.
- Change `$config['base_url']` in config.php
- Change DB credentials accordingly in database.php
- Import .sql file of create by database tables from raw SQL given above controller file. 
- Register a User.
- Login with that user to get the `access_token`.
- To perform crud operations you have to supply the `access_token` in header for `Authorization` with other data in body section.
- If `access_token` expired, you can also regenerate `access_token` by providing `username`.
- Logout & clear the session.

> [!IMPORTANT]
> Initially `access_token` has been set for 1 minute.

> [!WARNING]
> `jwt_key` must be changed for your own protection in production environment.

>Firebase admin sdk are using for backend api separately 

>Firebase configuration file suold be avaiable in config folder for Firbase admin 
Current file - code-bright-uat-firebase-adminsdk-tv6dy-f4a0f4765f


>It is used by Firebase admin sdk library 
*******************************
** App apis codeigniter section**
*********************************


