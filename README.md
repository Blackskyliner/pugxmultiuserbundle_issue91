pugxmultiuserbundle_#91
=======================

This Sandbox Project elaborates the following issue: https://github.com/PUGX/PUGXMultiUserBundle/issues/91

# Installation

1. composer install
2. php app/console appbundle:testdata:create

# Configuration

- None

# Bug reproduction

- Login as 'Administrator' with password '12345'
- Click the "Login as Blackskyliner" link
- Click the "Exit Impersonate" link

- It will log the following in your current env.log:
   ```
   security.INFO: Authentication exception occurred; redirecting to authentication entry point (Expected an instance of AppBundle\Entity\UserOne, but got "AppBundle\Entity\UserTwo".) [] []
   ```

- **What should happen**: We should see the home site with "Logged in as Administrator".
- **What happens**: We see the login and we are still logged in as "Blackskyliner" instead of "Administrator"
