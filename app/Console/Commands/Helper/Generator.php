<?php

namespace App\Console\Commands\Helper;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Generator
{
  use GeneratorTrait;
  
  public $base = [
    'template' => [
      'path' => 'resources/js/Pages/',
      //'path' => 'resources/test/js/Pages/',
      'extenstion' => '.vue'
    ],
    'controller' => [
      'path' => 'app/Http/Controllers/',
      //'path' => 'resources/test/Controllers/',
      'namespace' => 'App\Http\Controllers',
      'extenstion' => '.php',
    ],
    'request' => [
      'path' => 'app/Http/Requests/',
      //'path' => 'resources/test/Requests/',
      'namespace' => 'App\Http\Requests',
      'extenstion' => '.php',
      ],
    'resource' => [
      'path' => 'app/Http/Resources/',
      //'path' => 'resources/test/Resources/',
      'namespace' => 'App\Http\Resources',
      'extenstion' => '.php'
    ],
    'route' => [
      'path' => 'routes/',
      'extenstion' => '.php'
    ]
  ];
  public $stubs = [
    'resource' => 'stub/resource.stub',
    'controller' => 'stub/controller.stub',
    'request.store' => 'stub/request.stub',
    'request.update' => 'stub/request.stub',
    'template' => 'stub/page.stub',
    'route' => 'stub/route.stub',
  ];
  
  public function __construct($page = null, $extra = []){
    $this->propsPage = $page;
    $this->studyPage($extra)->discover();
  }
  
  public function generateReplaces($file_key){
    return [
      'request.store' => [
        'request class' => Str::of(Arr::get($this->namespaces, 'full.'.$file_key))->classBasename(),
        'namespace request' => Arr::get($this->namespaces, 'request'),
        'request_fields' => $this->loopFields('stub/request/request_fields.stub'),
      ],
      'request.update' => [
        'request class' => Str::of(Arr::get($this->namespaces, 'full.'.$file_key))->classBasename(),
        'namespace request' => Arr::get($this->namespaces, 'request'),
        'request_fields' => $this->loopFields('stub/request/request_fields.stub'),
      ],
      'resource' => [
        'resource class' => Str::of($this->namespaces['full']['resource'])->classBasename(),
        'namespace resource' => $this->namespaces['resource'],
        'resource_fields' => $this->loopFields('stub/resource/map-field.stub'),
      ],
      'controller' => [
        'namespace model' => $this->namespaces['model'],
        'namespace request create full' => $this->namespaces['full']['request']['store'],
        'namespace request update full' => $this->namespaces['full']['request']['update'],
        'namespace resource full' => $this->namespaces['full']['resource'],
        'Model' => Str::of($this->namespaces['model'])->classBasename(),
        'controller class' => Str::of($this->namespaces['full']['controller'])->classBasename(),
        'namespace controller' => $this->namespaces['controller'],
        
      ],
      'route' => [
        'controller class' => Str::of($this->namespaces['full']['controller'])->classBasename(),
        'controller class full' => $this->namespaces['full']['controller'],
        'route prefix' => $this->extra['route_prefix'],
      ],
      'template' => [
        'table_td' => $this->loopFields('stub/page/table-td.stub'),
        'table_th' => $this->loopFields('stub/page/table-th.stub'),
        'formgroup' => $this->loopFields('stub/page/form-group.stub'),
        'defineform' => $this->loopFields('stub/page/form-define.stub'),
        'formFilter' => $this->loopFields('stub/page/form-filter.stub'),
        'form-update-populate' => $this->loopFields('stub/page/form-update-populate.stub'),
        'table fields' => $this->loopFields('stub/page/table-columns.stub'),
      ]
    ][$file_key];
  }
}