Feature: Search collection
  In order to find specific field maps in an collection
  As a consumer
  I want to get all items in the collection matching a given criteria

  Scenario: Search a collection
    Given a GET endpoint "/testapi" which returns content type "application/hal+json" and body:
    """
    {
      "items": [
        {"name": "ball", "colour": "red"},
        {"name": "rabbit", "colour": "grey"},
        {"name": "shoe", "colour": "brown"}
      ]
    }
    """
    When I make a GET request to "/testapi"
    Then I should find 1 field with "name" matching "rabbit" in the "items" collection
    And the field should have "colour" "grey"
