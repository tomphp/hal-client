<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;
use TomPHP\HalClient\Client;
use TomPHP\HalClient\Exception\HalClientException;
use TomPHP\HalClient\Exception\UnknownContentTypeException;
use TomPHP\HalClient\HttpClient\DummyHttpClient;
use TomPHP\HalClient\HttpClient\GuzzleHttpClient;
use TomPHP\HalClient\Processor\HalJsonProcessor;
use TomPHP\HalClient\Resource\Node;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var DummyHttpClient */
    private $httpClient;

    /** @var Client */
    private $client;

    /** @var Response */
    private $response;

    /** @var HalClientException */
    private $error;

    /** @var Node */
    private $result;

    /** @var string */
    private $urlPrefix = '';

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($client)
    {
        if ($client === 'guzzle') {
            $this->httpClient = new GuzzleHttpClient();
            $this->urlPrefix = 'http://localhost:1080';
        } else {
            $this->httpClient = new DummyHttpClient();
        }

        $this->client = new Client($this->httpClient, [
            new HalJsonProcessor()
        ]);
    }

    /**
     * @Given a :method endpoint :url which returns content type :contentType and body:
     */
    public function aEndpointWhichReturnsContentTypeAndBody($method, $url, $contentType, PyStringNode $body)
    {
        $this->httpClient->createEndpoint($method, $url, $contentType, (string) $body);
    }

    /**
     * @When I make a GET request to :url
     */
    public function iMakeAGetRequestTo($url)
    {
        try {
            $this->response = $this->client->get($this->urlPrefix . $url);
        } catch (HalClientException $error) {
            $this->error = $error;
        }
    }

    /**
     * @When I make a GET request to link :linkName from the response
     */
    public function iMakeAGetRequestToLinkFromTheResponse($linkName)
    {
        $this->response = $this->response->getLink($linkName)->get();
    }

    /**
     * @Then I should get a bad content type error
     */
    public function iShouldGetABadContentTypeError()
    {
        Assert::assertInstanceOf(UnknownContentTypeException::class, $this->error);
    }

    /**
     * @Then the response field :field should contain :value
     */
    public function theResponseFieldShouldContain($field, $value)
    {
        Assert::assertEquals($value, $this->response->$field->getValue());
    }

    /**
     * @Then the response field :fieldName in embedded resource :resourceName should contain :value
     */
    public function theResponseFieldInEmbeddedResourceShouldContain($resourceName, $fieldName, $value)
    {
        Assert::assertEquals($value, $this->response->getResource($resourceName)->$fieldName->getValue());
    }

    /**
     * @Then the field :level2 in response field :level1 should contain :value
     */
    public function theResponseFieldInResponseFieldShouldContain($level1, $level2, $value)
    {
        Assert::assertEquals($value, $this->response->$level1->$level2->getValue());
    }

    /**
     * @Then the field :fieldName at index :index in response field :resourceName should contain :value
     */
    public function theFieldAtIndexInResponseFieldShouldContain($fieldName, $resourceName, $value, $index)
    {
        Assert::assertEquals($value, $this->response->{$resourceName}[$index]->$fieldName->getValue());
    }

    /**
     * @Then the field :fieldName at index :index in resource field :resourceName should contain :value
     */
    public function theFieldAtIndexInResourceFieldShouldContain($fieldName, $resourceName, $value, $index)
    {
        Assert::assertEquals($value, $this->response->getResource($resourceName)[$index]->$fieldName->getValue());
    }

    /**
     * @Then I should find :count field with :fieldName matching :fieldValue in the :collection collection
     */
    public function iShouldFindFieldWithMatchingInTheCollection($fieldName, $fieldValue, $collection, $count)
    {
        $result = $this->response->$collection->findMatching([$fieldName => $fieldValue]);

        Assert::assertCount((int) $count, $result);

        $this->result = $result[0];
    }

    /**
     * @Then the field should have :name :value
     */
    public function theFieldShouldHave($name, $value)
    {
        Assert::assertEquals($value, $this->result->$name->getValue());
    }
}
