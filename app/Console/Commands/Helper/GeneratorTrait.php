<?php
namespace App\Console\Commands\Helper;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use File;

trait GeneratorTrait {
  public $page, $propsPage, $filepath;
  public $extra = [];
  public $template = [
    '{{! ', ' !}}' 
  ];
  
  public $files = [];
  public $namespaces = [];
  
  
  
  
  public function discover(){
    $explode = array_map(function($part){
      return Str::studly($part);
    }, explode('.', $this->propsPage));
    foreach ($this->base as $key => $option){
      if(isset($option['namespace'])){
        $this->namespaces[$key] = $this->build_namespace($explode, $key);
        $this->namespaces['full'][$key] = $this->full_namespace($this->namespaces[$key], $explode, $key);
      }
      switch($key){
        case 'controller':
          $this->files[$key] = $this->base[$key]['path'].join('/', $explode).'Controller'.$this->base[$key]['extenstion'];
          break;
        case 'request':
          $this->files[$key] = [
            'store' => $this->base[$key]['path'].join('/', $explode).'/StoreRequest'.$this->base[$key]['extenstion'],
            'update' => $this->base[$key]['path'].join('/', $explode).'/UpdateRequest'.$this->base[$key]['extenstion'],
          ];
          break;
        case 'resource':
          $this->files[$key] = $this->base[$key]['path'].join('/', $explode).'Resource'.$this->base[$key]['extenstion'];
          break;
        case 'route':
          $this->files[$key] = $this->base[$key]['path'].'copy_route.php'.$this->base[$key]['extenstion'];
          break;
        default: 
          $this->files[$key] = $this->base[$key]['path'].join('/', $explode).$this->base[$key]['extenstion'];
          break;
      }
    }
  }
  
  public function build_namespace($parts, $file){
    if($file != 'request') unset($parts[count($parts) -1]);
    return $this->base[$file]['namespace'] . '\\' . join('\\', $parts);
  }
  
  public function full_namespace($namespace, $parts, $file){
    if($file == 'request') return [
      'store' => $namespace . '\\StoreRequest',  
      'update' => $namespace . '\\UpdateRequest',  
    ];
    
    return $namespace . '\\' . $parts[count($parts) - 1] . ucfirst($file);
  }
  
  public function studyPage($extra){
    $split = array_map(function($item){
      return ucfirst($item);
    }, explode('.', $this->propsPage));
    
    $this->page = $split[count($split) - 1];
    $this->extra = [
      'plural' => strtolower(Str::plural($this->page, 2)),
      'Plural' => ucfirst(Str::plural($this->page, 2)),
      'singular' => strtolower(Str::singular($this->page, 2)),
      'Singular' => ucfirst(Str::singular($this->page, 2)),
      'modal_id' => strtolower(Str::snake(join(' ', $split))) . '_create_modal',
      'route_name' => $extra['route_name'] ? $extra['route_name'].'.' : '',
      'route_prefix' => strtolower(Arr::last(explode('.', $extra['route_name']))),
      'fields' => explode(' ', $extra['fields']),
      'Inertia Page' => join('/', $split),
    ];
    
    $array_fields = '[';
    foreach (explode(' ', $extra['fields']) as $key => $field){
      $array_fields .= '"'.$field.'"';
      if ($key !== array_key_last(explode(' ', $extra['fields']))) {
        $array_fields .= ', ';
      }
    }
    $array_fields .= ']';
    $this->extra['array_fields'] = $array_fields;
    
    
    $spaceExplode = array_map(function($item) {
      return ucfirst($item);
    },explode('.', $extra['model_namespace']));
    $this->namespaces['model'] = 'App\Models\\' . join('\\', $spaceExplode);
    return $this;
  }
  
  public function replaces($replaces = []){
    return array_merge([
      'title' => strtolower($this->page),
      'Title' => ucfirst($this->page),
      'page' => $this->extra['singular'],
      'pa_ge' => Str::snake($this->extra['singular']),
      'paGe' => Str::camel($this->extra['singular']),
      'Page' => $this->extra['Singular'],
      'Pages' => $this->extra['Plural'],
      'pages' => $this->extra['plural'],
      'pa_ges' => Str::snake($this->extra['plural']),
      'paGes' => Str::camel($this->extra['plural']),
      'modal_id' => $this->extra['modal_id'],
      'route_name' => $this->extra['route_name'],
      'Inertia Page' => $this->extra['Inertia Page'],
      'array_fields' => $this->extra['array_fields'],
    ], $replaces);
  }
  
  
  public function replaceTemplate($content, array $replaces = []){
    foreach ($this->replaces() as $key => $value){
      $match = '/'.$this->template[0].$key.$this->template[1].'/';
      $content = preg_replace($match, $value, $content);
    }
    
    foreach ($replaces as $key => $value){
      $match = '/'.$this->template[0].$key.$this->template[1].'/';
      $content = preg_replace($match, $value, $content);
    }
    return $content;
  }

  public function generate($file, $forced = false){
    if(! file_exists(dirname(Arr::get($this->files, $file)))) mkdir(dirname(Arr::get($this->files, $file)), 0777, true);
    if (File::exists(Arr::get($this->files, $file)) && !$forced) {
      return [
        'message' => 'File In '.Arr::get($this->files, $file).' already exists.',
        'success' => 0,
        'file_exists' => 1,
      ];
    }
    
    $content = $this->replaceTemplate(
      file_get_contents(Arr::get($this->stubs, $file)),
      $this->generateReplaces($file)
    );
    
    File::put(Arr::get($this->files, $file), $content);
    
    return [
      'file' => Arr::get($this->files, $file),
      'message' => 'File In ['.Arr::get($this->files, $file).'] Created.',
      'success' => 1
    ];
  }
  
  public function loopFields($stub){
    $data= '';
    $loop = 0;
    foreach ($this->extra['fields'] as $field){
      $loop++;
      $append = $loop == count($this->extra['fields']) ? '' : PHP_EOL;
      $fieldReplace = [
        'table_field' => Str::snake($field),
        'table field' => strtolower(Str::replace('_', ' ', $field)),
        'Table Field' => ucwords(Str::replace('_', ' ', $field)),
        'Table field' => ucfirst(Str::replace('_', ' ', $field))
      ];
      
      $data .= $this->replaceTemplate(file_get_contents($stub), $fieldReplace) . $append;
    }
    return $data;
  }
}