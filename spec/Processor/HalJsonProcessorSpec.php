<?php

namespace spec\TomPHP\HalClient\Processor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\ResourceFetcher;
use Phly\Http\Response;

class HalJsonProcessorSpec extends ObjectBehavior
{
    /** @var Response */
    private $response;

    function let()
    {
        $body = fopen(
            'data://text/plain,{
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
                "map": {
                    "mapfield": "mapvalue"
                },
                "_embedded": {
                    "resource1": {
                        "subfield": "subvalue"
                    }
                }
            }',
            'r'
        );

        $this->response = new Response($body, 200, ['content-type' => 'application/hal+json']);
    }

    function it_returns_the_hal_plus_json_content_type()
    {
        $this->getContentType()->shouldReturn('application/hal+json');
    }

    function it_processes_simple_single_fields(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->field1->value()->shouldReturn('value1');
    }

    function it_processes_fields_which_are_maps(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->map->mapfield->value()->shouldReturn('mapvalue');
    }

    function it_processes_links(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->links()->shouldReturn(['self', 'other']);
    }

    function it_does_not_add_links_as_a_field(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->shouldThrow(new FieldNotFoundException('_links'))
                 ->duringField('_links');
    }

    function it_does_not_add_embedded_as_a_field(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->shouldThrow(new FieldNotFoundException('_embedded'))
                 ->duringField('_embedded');
    }

    function it_processes_resources(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->resource1->subfield->value()->shouldReturn('subvalue');
    }
}
