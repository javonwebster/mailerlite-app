## How to get Setup Locally

### Prerequisites

- A computer and an internet connection...
- [Docker](https://docs.docker.com/get-docker/)
- [A MailerLite account with access to an API Key](https://www.mailerlite.com/)

### Steps

- Pull down the repo ``git clone https://github.com/javonwebster/mailerlite-app.git``
- Start Docker
- In your terminal/command line, run: ``cd /mailerlite-app``
- Then make an ``.env`` file with the same contents of ``.env.example``. 
- Then run the command ``./vendor/bin/sail up -d`` (This will pull down all the images and spin up the containers)
- In your Database GUI of choice import the **mailerlite_app.sql** file in this repo. You will be able to connect to the database using the following credentials:
```
Host: 127.0.0.1 (some Database apps prefer "localhost")
Password: password
Port: 3306
Databse Name: mailerlite_app
```
- Then run ``./vendor/bin/sail composer install`` (This will pull on the packages for the APP)
- Run ``./vendor/bin/sail npm install``
- Run ``./vendor/bin/sail npm run dev``
- If all went well, you should be able to see the APP in your browser at [http://localhost/](http://localhost/)
- That's it!

### Running Tests

- Inside your .env file add your API Key like so:
```
TEST_MAILER_API_KEY=your-key-goes-here
```
- Once you have completed the above steps, inside your terminal run ``./vendor/bin/sail test``


