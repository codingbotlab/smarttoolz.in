CREATOR AI — GOOGLE LOGIN SETUP
================================

1. DATABASE
-----------
Import database.sql into MySQL.

Then edit config.php:
DB_HOST
DB_NAME
DB_USER
DB_PASS


2. GOOGLE CLOUD
---------------
Open Google Cloud Console:
https://console.cloud.google.com/

Create/select a project.

Create an OAuth Client ID:
APIs & Services -> Credentials -> Create Credentials -> OAuth client ID
Application type: Web application

Add an Authorized redirect URI EXACTLY matching:

https://YOUR-DOMAIN/auth/google-callback.php

Example:
https://example.com/creator-ai/auth/google-callback.php


3. CONFIGURE
------------
Put the Google Client ID and Client Secret into config.php:

const GOOGLE_CLIENT_ID = '...apps.googleusercontent.com';
const GOOGLE_CLIENT_SECRET = '...';
const GOOGLE_REDIRECT_URI = 'https://YOUR-DOMAIN/auth/google-callback.php';


4. FILE LOCATION
----------------
Put this auth folder where Creator AI can reach it.

If your Creator AI page is:
 /creator-ai/index.php

then use:
 /creator-ai/auth/login.php

and change the links in login.php/dashboard.php if your folder names differ.


5. GOOGLE LOGIN FLOW
--------------------
login.php
   -> google-login.php
   -> Google
   -> google-callback.php
   -> users table
   -> dashboard.php


SECURITY
--------
- OAuth state is generated with random_bytes and checked on callback.
- Session ID is regenerated after successful login.
- Google email verification is required.
- PDO prepared statements are used.
- Never commit Google Client Secret to a public repository.
- Use HTTPS in production.

Google's official PHP OAuth documentation recommends using a server-side OAuth flow and an exact registered redirect URI.
