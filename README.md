PHP Pimple Contacts Example
========================

The [Pimple][1] dependency injection container has been used to implement an IoC design pattern which should make testing and further project development easier in the future (e.g. supporting additional data sources).

To Run:
----------------------------------

***Assuming PHP & Composer are installed.***

1) First copy a full version of the contacts.json data file to the /data/ directory.

2) In the project directory, install third party dependencies:

    $ composer up 

3) From the parent directory, run the in-built PHP webserver:

    php -S localhost:8000 -t php-pimple-contacts-example
    
4) The project can now be viewed at the above specified url

Notes:
----------------------------------
Any questions or problems, please raise an issue on Github:

[https://github.com/Maltronic/php-pimple-contacts-example/issues][2]

[1]: http://pimple.sensiolabs.org/
[2]: https://github.com/Maltronic/php-pimple-contacts-example/issues
