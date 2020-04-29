# SF Talk Rating

Application integrated with SymfonyConnect, to give feedback over talks from SymfonyCon / SymfonyLive conferences.

## Setup

TL,DR:
`composer install`

Requires PHP >= 7.4 and MySQL. 
Works out-of-the-box with Symfony webserver and Docker (docker-compose config provided).

You need to create an OAuth app in your [SymfonyConnect account](https://connect.symfony.com/account/apps), 
and fill `SYMFONY_CONNECT_APP_ID` and `SYMFONY_CONNECT_APP_SECRET` environment variables.

To allow some Users to access admin, you must fill in `admins.yaml` file (cf. `admins.yaml.dist` for reference).
