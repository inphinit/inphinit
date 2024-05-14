<?php
namespace Controller;

use Inphinit\Routing\Resource;

class ResourceSample extends Resource
{
    public function __construct()
    {
        $this->format = Resource::SLASH|Resource::NOSLASH;
        $this->contentType = 'application/json; charset=UTF-8';
    }

    public function index() {
        return 'Index resource';
    }

    public function create() {
        return 'Form/screen for create resource';
    }

    public function store() {
        return 'Create resource';
    }

    public function edit($id) {
        return 'Edit resource';
    }

    public function show($id) {
        return 'Show resource';
    }

    public function update($id) {
        return 'Update resource';
    }

    public function destroy($id) {
        return 'Destroy resource';
    }

    protected static function output($output)
    {
        return json_encode(array(
            'body' => $output
        ));
    }
}
