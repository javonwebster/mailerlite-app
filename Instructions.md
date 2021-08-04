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
- Run ```docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v $(pwd):/opt \
  -w /opt \
  laravelsail/php74-composer:latest \
  composer install --ignore-platform-reqs```
- In the project directory run the command ``./vendor/bin/sail up -d``
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

### Done?

- When you're done you can run ``./vendor/bin/sail down``

### Troubleshooting
The benefit of using docker is that it minimizes the chances of something going wrong due to configuration 
differences on Host machines. However, things can still go wrong.
If you've used docker before, you may run into an issue where your containers may start and exit unexpectedly. In general,
the quickest fix is usually to delete any old images and containers to free up sapce(or if you really want to get drastic you can delete all of them).
To confirm all your containers are up, simply run ``docker ps`` in your terminal.


