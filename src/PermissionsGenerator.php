<?php 

namespace ZakariaJawas\PermissionsGenerator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PermissionsGenerator {

    public function generate() {

        if (!Schema::hasTable('permissions')) {

            return "Please install spatie permissions package first, and run php artisan migrate then run permissions:generate command";
        } //end if
        //get models
        $models = $this->getModels();

        //check if models exists
        if (count($models) == 0) {

            return "You don't have any models yet, create models then run the command again\n";            
        } //end if
        
        
        //process models 
        $processedModels = $this->processModels($models);

        foreach($processedModels as $model) {

            $this->generatePermissions($model);
        } //end for each
        return "Success\n";
    }


    private function getModels(): Collection
    {
        
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                                
                $class = sprintf('\%s%s',
                    Container::getInstance()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                                        
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract() && !(array_search($reflection->name, Config::get("permissionsgenerator.exclude"), false) > -1);
                }

                return $valid;
            });

        return $models->values();
    }

    /**
     * Used to convert models name into permissions names
     * Rules:-
     * Lowercased
     * Compund Names are Separated with Spaces
     */
    private function processModels(Collection $models) {

        $result = [];
        foreach ($models as $model) {

            //extract model class name
            $modelSections = explode('\\', "$model");
            $modelName = end($modelSections);

            //split Pascal model name into array of names            
            $matches =  preg_split('/(?=[A-Z]{1}[a-z]+)/', $modelName, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            $name = strtolower(implode(' ',  $matches));
            $result[] = $name;
        } 
        return $result;
    }

    private function generatePermissions($model) {

        $prefix = Config::get("permissionsgenerator.prefix");        
        $permissions = Config::get("permissionsgenerator.permissions");

        $query = [];
        foreach($permissions as $permission) {

            if (empty($prefix)) {

                $query[] = ['name' => "$permission $model", 'guard_name' => 'web'];            
            } else {
                
                $query[] = ['name' => "$prefix $permission $model", 'guard_name' => 'web'];            
            } //end if            
        } //end for
        
        DB::table('permissions')->insert($query);
    }
}