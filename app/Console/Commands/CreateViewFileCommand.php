<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateViewFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {input}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new view file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $input = $this->argument("input");
        $view_path = $this->viewPath($input);
    
        $create_file = $this->createFile($view_path);
        if($create_file === true) {
            File::put($view_path,"");
            return $this->info("View file created successfully to {$view_path}");
        }
    }


    public function viewPath(string $view_name) {
        if(Str::contains($view_name, '\\')) {
            $filter_name = str_replace("\\","/",$view_name);
        }else if(Str::contains($view_name,".")) {
            $filter_name = str_replace(".","/",$view_name);
        }else {
            $filter_name = $view_name;
        }

        $concat_view_ext = $filter_name . ".blade.php";
        $view_path = resource_path("views/".$concat_view_ext);
        return $view_path;
    }

    public function createFile(string $view_path) {
        if(File::exists($view_path)) {
            $this->error("This file is already exists in this location");
            return false;
        }else {
            $dirname = dirname($view_path);
            if(!file_exists($dirname)) {
                mkdir($dirname,0777,true);
            }
        }

        return true;
    } 
}
