# Automated Testing for OC

## Instructions


1. Install Open Classifieds. 

    Admin must have:<br>
    Email: admin@reoc.lo<br>
    Password: 1234


2. Upload all premium themes into _/themes_ folder.


3. Download [this file](https://mega.nz/#!A41ghCJL!dDIXPWZ9NOvRscw0STOsYNoOMGH6dAtk6Atcc1pD2LI) and upload it On panel, Tools -> Import. Then, click PROCESS. 


4. Go to the _oc/_ directory, to dowload codecept.phar

    wget http://codeception.com/codecept.phar


5. Run the first test:

        php codecept.phar run acceptance test-oc/admin/SetUsersPasswordsCept

    This test changes the passwords of users.


6. Run all the tests:

        php codecept.phar run acceptance

    To see all the steps for each test run this command (Optional, not recommended for this case)

        php codecept.phar run acceptance --steps



**Notice:** To run tests you have to be into the _oc/_ directory 


## Generate scenarios

Generates user-friendly text scenarios from scenario-driven tests.

    php codecept.phar g.scenarios acceptance --path tests/docs
    

    

