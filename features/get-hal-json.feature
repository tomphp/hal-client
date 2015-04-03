Feature: Get HAL JSON
  In order to explore HAL APIs
  As a consumer
  I want results from requests to be easy to navigate

  Scenario: Not a JSON HAL API
    Given a GET endpoint "/testapi" which returns content type "application/bad-type" and body:
    """
    {}
    """
    When I make a GET request to "/testapi"
    Then I should get a bad content type error

  Scenario: Simple single level resource
    Given a GET endpoint "/testapi" which returns content type "application/hal+json" and body:
    """
    {
      "name": "Tom Oram",
      "twitter": "tomphp"
    }
    """
    When I make a GET request to "/testapi"
    Then the response field "name" should contain "Tom Oram"
    And the response field "twitter" should contain "tomphp"

  Scenario: A field is a map
    Given a GET endpoint "/testapi" which returns content type "application/hal+json" and body:
    """
    {
      "social": {
        "twitter": "tomphp"
       }
    }
    """
    When I make a GET request to "/testapi"
    Then the field "twitter" in response field "social" should contain "tomphp"

  Scenario: A field is a list
    Given a GET endpoint "/testapi" which returns content type "application/hal+json" and body:
    """
    {
      "social": [
        {"name": "Facebook"},
        {"name": "Twitter"}
       ]
    }
    """
    When I make a GET request to "/testapi"
    Then the field "name" at index 0 in response field "social" should contain "Facebook"
    And the field "name" at index 1 in response field "social" should contain "Twitter"

  @no-guzzle
  Scenario: Following a link
    Given a GET endpoint "/page1" which returns content type "application/hal+json" and body:
    """
    {
      "_links": {
        "next": {
          "href": "/page2"
        }
      },
      "name": "Fred"
    }
    """
    And a GET endpoint "/page2" which returns content type "application/hal+json" and body:
    """
    {
      "name": "Ted"
    }
    """
    When I make a GET request to "/page1"
    And I make a GET request to link "next" from the response
    Then the response field "name" should contain "Ted"

  Scenario: Embedded resource
    Given a GET endpoint "/testapi" which returns content type "application/hal+json" and body:
    """
    {
      "_embedded": {
        "image": {
          "name": "thing.jpg"
        }
      },
      "name": "Fred"
    }
    """
    When I make a GET request to "/testapi"
    Then the response field "name" in embedded resource "image" should contain "thing.jpg"

  Scenario: Embedded resource collection
    Given a GET endpoint "/testapi" which returns content type "application/hal+json" and body:
    """
    {
      "_embedded": {
        "images": [{
          "name": "thing.jpg"
        }]
      },
      "name": "Fred"
    }
    """
    When I make a GET request to "/testapi"
    Then the field "name" at index 0 in resource field "images" should contain "thing.jpg"
