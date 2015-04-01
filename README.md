HalClient
=========

[![Build Status](https://api.travis-ci.org/tomphp/hal-client.svg)](https://travis-ci.org/tomphp/hal-client)

A client library for navigating HAL APIs.

Installation
------------

```
$ composer require tomphp/hal-client
```

### Stability 

This library is currently in an early, experimental development phase. There
are many missing features and the architecture may well change.  These
architecture changes may change the types of objects being returned but will
not make any changes to the public interface (although they might expand in)
during version "0.1".

Therefore, if you wish to use this, set the composer requirement to version
"~0.1" and stick the using only the methods in the example below.

Example
-------

Given an API which looks like this:

**GET http://api.demo-cocktails.com/recipes**
```json
{
    "_links": {
        "self": {
            "href": "http://api.demo-cocktails.com/recipes"
        }
    },
    "count": 3,
    "_embedded": {
        "recipes": [
            {
                "_links": {
                    "self": {
                        "href": "http://api.demo-cocktails.com/recipes/1"
                    }
                },
                "name": "Mojito"
            },
            {
                "_links": {
                    "self": {
                        "href": "http://api.demo-cocktails.com/recipes/2"
                    }
                },
                "name": "Pina Colada"
            },
            {
                "_links": {
                    "self": {
                        "href": "http://api.demo-cocktails.com/recipes/3"
                    }
                },
                "name": "Daquiri"
            }
        ]
    }
}
```

**GET http://api.demo-cocktails.com/recipes/1**
```json
{
    "_links": {
        "self": {
            "href": "http://api.demo-cocktails.com/recipes/1"
        }
    },
    "name": "Mojito",
    "rating": 5,
    "ingredients": [
        {"name": "White Rum"},
        {"name": "Soda"},
        {"name": "Lime Juice"},
        {"name": "Sugar"},
        {"name": "Mint Leaves"}
    ]
}

```

```php
<?php

use TomPHP\HalClient\Client;

$recipes = Client::create()->get('http://api.demo-cocktails.com/recipes');

echo "There are currently " . $recipes->count->getValue() . " cocktails" . PHP_EOL;

$cocktail = $recipes[0]->self->get();
// or
$cocktail = $recipes->findMatching(['name' => 'Mojito'])[0]->self->get();

echo $cocktail->name->getValue() . " has a " . $cocktail->rating->getValue() . " start rating." .PHP_EOL;
```

### Methods

```php
<?php

use TomPHP\HalClient\Client;

$resource = Client::create();

// Methods for resources

$resource->then_name; // Access a field, link or embedded resource named 'the_name'

$resource->getField('field_name'); // Specifically access field named 'field_name'
$resource->getLink('link_name'); // Specifically access link named 'link_name'
$resource->getResouce('resource_name'); // Specifically access resource named 'resource_name'

// Methods for fields
$field->getValue(); // Return the value contained in the field
$field->person->name->getValue(); // Access a sub field by name

// Methods for links
$link->get(); // Makes a get request to the link's href and returns the resource

// Methods for collections
$coll[5]; // Access element 5 in a collection

$coll->findMatching(['age' => 20]); // Return a collection with all maps in the collection which a field called 'age' which is set to 20.
```

Restrictions
------------

Currently the library can only make GET requests to HAL+JSON APIs.

Planned Features
----------------
* Better error reporting
* Iterators for walking the trees
* HAL+XML Processor
* Raw JSON Processor
* Raw XML Processor
* Other HTTP methods for updating

Contributing
------------
Please do!

PSR-2 and tests required.
