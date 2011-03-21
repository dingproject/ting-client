# Ting DBC PHP5 Client

The Ting DBC PHP5 Client is a library for accessing the Ting API developed by DBC using PHP5.

The client currently supports:

* Searching the API

The client has been developed for use with [Drupal 6](http://drupal.org/) and [Zend Framework](http://framework.zend.com/) but the design is framework agnostic and developers can extend it for use with other frameworks.

## Tests

The client is unit tested using [SimpleTest](http://simpletest.org/) which is included in the ``vendor`` directory:

* To run internal tests:

    php TingClientTestSuite.php

* To run tests against the live Ting API using [Zend Framework](http://framework.zend.com/) (Requires Zend Framework available from PHP include path):

    php TingClientLiveZfTest.php

