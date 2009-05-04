# Ting DBC PHP5 Client

The Ting DBC PHP5 Client is a library for accessing the Ting API developed by DBC using PHP5.

The client currently supports:

* Searching the API

The client has been developed for use with [Drupal 6](http://drupal.org/) and [Zend Framework](http://framework.zend.com/) but the design is framework agnostic and developers can extend it for use with other frameworks.

## Usage

All use of the Ting API requires creating and configuring a TingClient object which determines how the client will access the API.

This example shows how the project is set up using HTTP and the [Zend Framework](http://framework.zend.com/). 

		$client = new TingClient(
								new TingClientZfHttpRequestAdapter(
									new Zend_Http_Client(),
									new TingClientHttpRequestFactory($this->baseUrl)), 
								new TingClientJsonResponseAdapter()
							);	

### Search

To search the Ting API:

* Setup the ``TingClient``
* Configure the search query:
		
		$search = new TingClientSearchRequest('dc.title:danmark');
		$search->setStart(1);
		$search->setNumResults(10);
		$result = $client->search($search);

## Adapters

The Ting Client uses two primary classes:

* ``TingClientRequestAdapter`` which is responsible for sending queries using a specific protocol e.g. HTTP (for REST) or SOAP
* ``TingClientResponseAdapter`` which is responsible for parsing results using a specific format e.g. JSON or XML

### Request

The Ting Client is bundled with request adapters using HTTP based on the following frameworks:

* [Drupal 6](http://drupal.org/): ``TingClientDrupal6HttpRequestAdapter``
* [Zend Framework](http://framework.zend.com/): ``TingClientZfHttpRequestAdapter``

Other protocols can be supported by adding new classes implementing the ``TingClientRequestAdapter`` interface.

Other frameworks can be supported under HTTP by adding new classes extended the ``TingClientHttpRequestAdapter`` abstract class.

### Response

The Ting Client is bundled with response adapters supporting the following formats:

* [JSON](http://framework.zend.com/): ``TingClientJsonResponseAdapter``

Other formats can be supported by adding new classes implementing the ``TingClientResponseAdapter`` interface.

## Tests

The client is unit tested using [SimpleTest](http://simpletest.org/) which is included in the ``vender`` directory:

* To run internal tests:
    
		php TingClientTestSuite.php
    
* To run tests against the live Ting API using [Zend Framework](http://framework.zend.com/) (Requires Zend Framework available from PHP include path):

		php TingClientLiveZfTest.php
  