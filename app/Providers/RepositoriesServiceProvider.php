<?php


namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class RepositoriesServiceProvider extends ServiceProvider
{
    protected $overrides = [
        //custom repository register example
        //'PermissionRepository' => 'NewPermissionRepository'
    ];

    public function __construct($app)
    {
        parent::__construct($app);

        //Auto binding repositories
        $contractsFolder = app_path('Repositories/Interfaces');
        $directory = new RecursiveDirectoryIterator($contractsFolder);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $name => $value) {
            $contract = strstr($name, 'app/') ?: strstr($name, 'app\\');
            $contract = rtrim($contract, '.php');

            $contractName = str_replace('/', '\\', ucfirst($contract));

            //Extract repository from contract
            $repositoryClass = str_replace('Interfaces\\', '', $contractName);
            $repositoryName = str_replace('Interface', '', $repositoryClass);

            //Replace repository if exist in overrides array
            $path = explode('\\', $repositoryName);
            $repositoryClassName = array_pop($path);
            if(isset($this->overrides[$repositoryClassName])){
                $repositoryName =  str_replace($repositoryClassName, $this->overrides[$repositoryClassName], $repositoryName);
            }

            if (interface_exists($contractName) && class_exists($repositoryName) &&  in_array($contractName, class_implements($repositoryName))) {
                $this->bindings[$contractName] = $repositoryName;
            }
        }
    }
}