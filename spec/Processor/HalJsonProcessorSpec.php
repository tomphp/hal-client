<?php

namespace spec\TomPHP\HalClient\Processor;

use Phly\Http\Response;
use PhpSpec\ObjectBehavior;
use TomPHP\HalClient\Exception\FieldNotFoundException;
use TomPHP\HalClient\Exception\ProcessingException;
use TomPHP\HalClient\ResourceFetcher;

class HalJsonProcessorSpec extends ObjectBehavior
{
    /** @var Response */
    private $response;

    function let()
    {
        $body = 'data://text/plain,{
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
            "collection": [
                {"name": "item1"}
            ],
            "_embedded": {
                "resource1": {
                    "subfield": "subvalue"
                },
                "resourcecollection": [
                    {"name": "collectionvalue"}
                ]
            }
        }';

        $this->response = new Response($body, 200, ['content-type' => 'application/hal+json']);
    }

    function it_returns_the_hal_plus_json_content_type()
    {
        $this->getContentType()->shouldReturn('application/hal+json');
    }

    function it_throws_for_bad_json_error(ResourceFetcher $fetcher)
    {
        $json = '{bad, josn}';
        json_decode($json);
        $error = json_last_error_msg();

        $response = new Response("data://text/plain,$json", 200, ['content-type' => 'application/hal+json']);

        $this->shouldThrow(ProcessingException::badJson($error))
             ->duringProcess($response, $fetcher);
    }

    function it_processes_simple_single_fields(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->field1->getValue()->shouldReturn('value1');
    }

    function it_processes_fields_which_are_maps(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->map->mapfield->getValue()->shouldReturn('mapvalue');
    }

    function it_processes_fields_which_are_collections(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->collection[0]->name->getValue()->shouldReturn('item1');
    }

    function it_processes_links(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->getLinks()->shouldReturn(['self', 'other']);
    }

    function it_does_not_add_links_as_a_field(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->shouldThrow(new FieldNotFoundException('_links'))
                 ->duringGetField('_links');
    }

    function it_does_not_add_embedded_as_a_field(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->shouldThrow(new FieldNotFoundException('_embedded'))
                 ->duringGetField('_embedded');
    }

    function it_processes_resources(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->getResource('resource1')->subfield->getValue()->shouldReturn('subvalue');
    }

    function it_processes_resources_collections(ResourceFetcher $fetcher)
    {
        $resource = $this->process($this->response, $fetcher);

        $resource->getResource('resourcecollection')[0]->name->getValue()->shouldReturn('collectionvalue');
    }
}
