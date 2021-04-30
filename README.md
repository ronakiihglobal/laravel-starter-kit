## About Laravel starter kit

This is public repo for starting with Laravel project + Basic authentication using [Laravel/sanctum](https://laravel.com/docs/8.x/sanctum) .

It can be used as starter kit for normal web application as well as for APIs + Frontend ( React/Vue ) or for only APIs.

## Steps for setup

- Create folder for your project. Ex `mkdir my-project` and `cd my-project`
- Next in `my-project` folder run this git clone command, note last `.` which will not create new folder. `git clone git@github.com:ronakiihglobal/laravel-starter-kit.git .`
- Next run `composer install`
- Next `cp .env.example .env` for generate .env file.
- Next set database and mail credentials in .env file.
- Run `php artisan key:generate`
- Run `php artisan migrate`
- Your done, Now you can run `php artisan serve`
- Also please dont miss to change `cors.php` and `sanctum.php` config files based on your domain.



## For normal web application with session based authentication

There are below routes

- GET /registration
	- This is get route for registration form which will submit form on post route /register.
	- This form has basic fields Name, Email, Password and confirm password.
	- On successfully submit, it will redirect to / page. This page will show simply user email and logout option.


- GET /login
	- This is get route for login form which will submit form on post route /login.
	- This form has basic fields Email, Password and remember-me.
	- On successfully submit, it will redirect to / page. This page will show simply user email and logout option.

- GET /logout
	- This is get route for logout which will simply invalidate session and redirect user to login page.


- GET /forgot-password
	- This is get route for forgot-password form which will submit form to post route /forgot-password.
	- This form has only Email field.
	- On successfully submit, it will send reset password link to user via mail.


- GET /reset-password/{token}
	- This is get route for reset-password form which will submit form to post route /reset-password.
	- This form has fields Email, New password, Confirm password and hidden field for reset password token.
	- On successfully submit, it will reset password to new password. 
	


## For SPA web application with separate frontend like React/VueJs and session based authentication

First need to setup [first-party-domain](https://laravel.com/docs/8.x/sanctum#configuring-your-first-party-domains). For this there is variable named `stateful` in sanctum.php config file.

Then on page load need to call one API /sanctum/csrf-cookie. This API will set cookie named `XSRF-TOKEN` and on each subsequent API call we will have to pass this value of `XSRF-TOKEN` cookie as `X-XSRF-TOKEN` header.


There are below routes

- POST /register

	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- X-XSRF-TOKEN: {XSRF-TOKEN}

	- This is post route for registration form which will submit data on post route /register.

	- This JSON has basic fields Name, Email, Password and confirm password.
		`{
			  "email":"ronak.iihgloal@gmail.com",
			  "password":"ronak@123",
			  "password_confirmation":"ronak@123",
			  "name":"Ronak patel"
		}`

	- On successfully submit, it will return access token. Token will be something like 
	`4|whSy5DtICrqk2Fk3rAnwoFdgbBycVlpxF5KLACWW`


- POST /login
	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- X-XSRF-TOKEN: {XSRF-TOKEN}

	- This is post route for login, which will submit data on post route /login.

	- This JSON has basic fields Email, Password and Device name (Optional).
		`{
			"email":"ronak.iihglobal@gmail.com",
			"password":"ronak@123",
			"device_name":"my awesome mobile app"
		}
		`

	- On successfully submit, it will return access token.

- GET /api/user

	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- X-XSRF-TOKEN: {XSRF-TOKEN}

	- This is get route which will simply return JSON object of current user

- GET /logout

	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- X-XSRF-TOKEN: {XSRF-TOKEN}

	- This is get route for logout which will simply delete access token.


- POST /api/forgot-password
	
	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- X-XSRF-TOKEN: {XSRF-TOKEN}
		
	- This is post route for forgot-password, which will submit data to post route /forgot-password.

	- This JSON has only `email` field.

	- On successfully submit, it will send reset password link to user via mail.


- POST /api/reset-password

	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- X-XSRF-TOKEN: {XSRF-TOKEN}

	- This is post route for reset-password which will submit data to post route /reset-password.

	- This JSON has fields Email, New password, Confirm password and reset password token.

	`{
		"email":"ronak.iihglobal@yopmail.com",
		"token":"f195eb37d328571d5f05c933437fb6569bebcc4280463e0fc807b8507a36e82b",
		"password":"ronak@123",
		"password_confirmation":"ronak@123"
	}`

	- On successfully submit, it will reset password to new password.


## For token based authentication ( Mobile app or web app)


There are below routes

- POST /api/register

	- Headers
		- Content-Type: application/json
		- Accept: application/json

	- This is post route for registration form which will submit data on post route /register.

	- This JSON has basic fields Name, Email, Password and confirm password.
		`{
			  "email":"ronak.iihgloal@gmail.com",
			  "password":"ronak@123",
			  "password_confirmation":"ronak@123",
			  "name":"Ronak patel"
		}`

	- On successfully submit, it will return access token. Token will be something like 
	`4|whSy5DtICrqk2Fk3rAnwoFdgbBycVlpxF5KLACWW`


- POST /api/login
	- Headers
		- Content-Type: application/json
		- Accept: application/json

	- This is post route for login, which will submit data on post route /login.

	- This JSON has basic fields Email, Password and Device name (Optional).
		`{
			"email":"ronak.iihglobal@gmail.com",
			"password":"ronak@123",
			"device_name":"my awesome mobile app"
		}
		`

	- On successfully submit, it will return access token.

- GET /api/user

	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- Authorization: Bearer {access token}

	- This is get route which will simply return JSON object of current user

- GET /api/logout

	- Headers
		- Content-Type: application/json
		- Accept: application/json
		- Authorization: Bearer {access token}

	- This is get route for logout which will simply delete access token.


- POST /api/forgot-password
	
	- Headers
		- Content-Type: application/json
		- Accept: application/json
		
	- This is post route for forgot-password, which will submit data to post route /forgot-password.

	- This JSON has only `email` field.

	- On successfully submit, it will send reset password link to user via mail.


- POST /api/reset-password

	- Headers
		- Content-Type: application/json
		- Accept: application/json

	- This is post route for reset-password which will submit data to post route /reset-password.

	- This JSON has fields Email, New password, Confirm password and reset password token.

	`{
		"email":"ronak.iihglobal@yopmail.com",
		"token":"f195eb37d328571d5f05c933437fb6569bebcc4280463e0fc807b8507a36e82b",
		"password":"ronak@123",
		"password_confirmation":"ronak@123"
	}`

	- On successfully submit, it will reset password to new password. 