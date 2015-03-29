<?php

namespace spec\TomPHP\HalClient\Processor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\HttpResponse;
use TomPHP\HalClient\Exception\FieldNotFoundException;

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
                    }
                },
                "field1": "value1"
            }'
        );
    }

    function it_returns_the_hal_plus_json_content_type()
    {
        $this->getContentType()->shouldReturn('application/hal+json');
    }

    function it_processes_simple_single_fields()
    {
        $response = $this->process($this->httpResponse);

        $response->field('field1')->shouldReturn('value1');
    }

    function it_processes_links()
    {
        $response = $this->process($this->httpResponse);

        $response->links()->shouldReturn(['self']);
    }

    function it_does_no_add_links_as_a_field()
    {
        $response = $this->process($this->httpResponse);

        $response->shouldThrow(new FieldNotFoundException('_links'))
                 ->duringField('_links');
    }
}
