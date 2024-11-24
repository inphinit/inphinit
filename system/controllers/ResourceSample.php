<?php
namespace Controller;

class ResourceSample extends \Inphinit\Routing\Resource
{
    public function __construct()
    {
        $this->format = self::SLASH | self::NOSLASH;
    }

    public function index()
    {
        return 'index';
    }

    public function create()
    {
        return 'create';
    }

    public function store()
    {
        return 'store';
    }

    public function show()
    {
        return 'show';
    }

    public function edit()
    {
        return 'edit';
    }

    public function update()
    {
        return 'update';
    }

    public function destroy()
    {
        return 'destroy';
    }
}
