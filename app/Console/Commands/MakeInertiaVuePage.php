<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Helper\Generator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use File;

class MakeInertiaVuePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:page {page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate inertia vue3 page.';
    
    public $page, $fullname;
    public $files = [];

    public function handle()
    {
        $fields = $this->ask('Fields you want to implement');
        $model_namespace = $this->ask('Model namespace?');
        $routeName = $this->ask('Base route name');
        $extra = [
          'fields' => $fields,
          'route_name' => $routeName, 
          'model_namespace' => $model_namespace
        ];
        $this->generator = new Generator($this->argument('page'), $extra);
        $files = [
          'template', 'controller', 'resource', 'request.store', 'request.update', 'route'
        ];
        
        
        foreach ($files as $file){
          $this->generateFile($file);
        }
        
        $this->table(
            ['name'],
            $this->files
        );
        
        $this->info("Have a relux. ". count($this->files). " files has been generated.");
    }
    
    private function generateFile($file){
      $this->info('Generating '. str_replace('.', ' ', $file) .'...');
      $response = $this->generator->generate($file, ($file == 'route'));
        if($response['success']){
          $this->info($response['message']);
          $this->files[] = ['name' => $response['file']];
        }else if(isset($response['file_exists']) && $response['file_exists']) {
          $this->error($response['message']);
          if($this->confirm('Do you want to replace the file?')){
            $response2 = $this->generator->generate($file, true);
            if($response2['success']) {
              $this->info($response2['message']);
              $this->files[] = ['name' => $response2['file']];
            }else{
              $this->error($response['message']);
            }
          }
        }else{
          $this->error($response['message']);
        }
    }
}
