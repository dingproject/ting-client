Ting DBC PHP5 Client
====================

A library for accessing the [Ting][] API developed by [DBC][] using PHP5.

The client currently supports:

* Searching the API
* Content recommendation
* Spelling and autocomplete suggestions

The client has been developed for use with [Drupal][] and [Zend Framework][].
The design is framework agnostic and developers can extend it for use with
other frameworks.

Running tests
-------------

The client is unit tested using [SimpleTest][] which is included in the
`vendor` directory:

To run tests against the live Ting API using Zend Framework
(Requires Zend Framework available from PHP include path), go to the
test folder and run `php TingClientLiveTestSuite.php`


#### Zend Framework tip ####

If you'd rather not install the Zend Framework system-wide, you can
download the Zend Framework package, and put the `library/Zend` folder
from the package inside the `test` folder of the Ting client.

[Ting]: http://ting.dk/
[DBC]: http://dbc.dk/
[Drupal]: http://drupal.org/
[Zend Framework]: http://framework.zend.com/
[SimpleTest]: http://simpletest.org/

