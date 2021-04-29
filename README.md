## About Laravel starter kit

This is public repo for starting with Laravel project + Basic authentication using [Laravel/sanctum](https://laravel.com/docs/8.x/sanctum) .

It can be used as starter kit for normal web application as well as for APIs + Frontend ( React/Vue ) or only APIs.



## For normal web application with session based authentication

There are below routes

- /registration
	- This is get route for registration form which will submit form on post route /register.
	- This form has basic fields Name, Email, Password and confirm password.
	- On successfully submit, it will redirect to / page. This page will show simply user email and logout option.


- /login
	- This is get route for login form which will submit form on post route /login.
	- This form has basic fields Email, Password and remember-me.
	- On successfully submit, it will redirect to / page. This page will show simply user email and logout option.

- /logout
	- This is get route for logout which will simply invalidate session and redirect user to login page.


- /forgot-password
	- This is get route for forgot-password form which will submit form to post route /forgot-password.
	- This form has only Email field.
	- On successfully submit, it will send reset password link to user via mail.


- /reset-password/{token}
	- This is get route for reset-password form which will submit form to post route /reset-password.
	- This form has fields Email, New password, Confirm password and hidden field for reset password token.
	- On successfully submit, it will reset password to new password. 
	


If calling from web then first call /sanctum/csrf-cookie and use 'X-XSRF-TOKEN': getCookie("XSRF-TOKEN"), in every next requests
device_name for token title 

