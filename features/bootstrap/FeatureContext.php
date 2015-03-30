<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;
use TomPHP\HalClient\HttpClient\DummyHttpClient;
use TomPHP\HalClient\Client;
use TomPHP\HalClient\Exception\UnknownContentTypeException;
use TomPHP\HalClient\Exception\HalClientException;
use TomPHP\HalClient\Processor\HalJsonProcessor;
use TomPHP\HalClient\HttpClient\GuzzleHttpClient;

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
            $this->response = $this->client->get($url);
        } catch (HalClientException $error) {
            $this->error = $error;
        }
    }

    /**
     * @When I make a GET request to link :linkName from the response
     */
    public function iMakeAGetRequestToLinkFromTheResponse($linkName)
    {
        $this->response = $this->response->$linkName->get();
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
        Assert::assertEquals($value, $this->response->$field->value());
    }


    /**
     * @Then the response field :field in embedded resource :resource should contain :value
     */
    public function theResponseFieldInEmbeddedResourceShouldContain($field, $resource, $value)
    {
        Assert::assertEquals($value, $this->response->$resource->$field->value());
    }
}
