<?php

namespace spec\TomPHP\HalClient\Processor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\HttpResponse;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\ResponseFetcher;

class HalJsonProcessorSpec extends ObjectBehavior
{
    /** @var HttpResponse */
    private $httpResponse;

    function let()
    {
        $this->httpResponse = new HttpResponse(
            'application/hal+json',
            '{
                "_links": {
                    "self": {
                        "href": "http://www.somewhere.com/",
                        "rel": "self"
                    },
                    "other": {
                        "href": "http://www.somewhere.com/other"
                    }
                },
                "field1": "value1",
                "_embedded": {
                    "resource1": {
                        "subfield": "subvalue"
                    }
                }
            }'
        );
    }

    function it_returns_the_hal_plus_json_content_type()
    {
        $this->getContentType()->shouldReturn('application/hal+json');
    }

    function it_processes_simple_single_fields(ResponseFetcher $fetcher)
    {
        $response = $this->process($this->httpResponse, $fetcher);

        $response->field('field1')->value()->shouldReturn('value1');
    }

    function it_processes_links(ResponseFetcher $fetcher)
    {
        $response = $this->process($this->httpResponse, $fetcher);

        $response->links()->shouldReturn(['self', 'other']);
    }

    function it_does_not_add_links_as_a_field(ResponseFetcher $fetcher)
    {
        $response = $this->process($this->httpResponse, $fetcher);

        $response->shouldThrow(new FieldNotFoundException('_links'))
                 ->duringField('_links');
    }

    function it_does_not_add_embedded_as_a_field(ResponseFetcher $fetcher)
    {
        $response = $this->process($this->httpResponse, $fetcher);

        $response->shouldThrow(new FieldNotFoundException('_embedded'))
                 ->duringField('_embedded');
    }

    function it_processes_resources(ResponseFetcher $fetcher)
    {
        $response = $this->process($this->httpResponse, $fetcher);

        $response->resource1->subfield->value()->shouldReturn('subvalue');
    }
}
