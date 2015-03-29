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
    Then the request field "name" should contain "Tom Oram"
    And the request field "twitter" should contain "tomphp"
