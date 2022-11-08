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

    /**
     * Generate permissions for your project models
     */
    public function generate() {

        //check if permissions table exists
        if (!Schema::hasTable('permissions')) {

            return "Please install spatie permissions package first, and run php artisan migrate then run permissions:generate command";
        } //end if

        //get project models
        $models = $this->getModels();

        //check if project conains any models
        if (count($models) == 0) {

            return "You don't have any models yet, create models then run the command again\n";            
        } //end if
        
        
        //process models => extract models names and convert it it permission style name
        $processedModels = $this->processModels($models);
        
        foreach($processedModels as $model) {

            $this->generatePermissions($model);
        } //end for each

        return "Permissions are generated, check your table.\n";
    }


    /**
     * Get a collection of the project models
     * @return Collection
     */
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
     * Used to convert models name into permissions style names
     * Rules:-
     * Lowercased
     * Compund Names are Separated with Spaces
     * 
     * @param Collection $models
     * @return Array
     */
    private function processModels(Collection $models) {

        $result = [];
        foreach ($models as $model) {

            //extract model name from class path
            $modelSections = explode('\\', "$model");
            $modelName = end($modelSections);

            //split Pascal model name into array of names          
            $matches =  preg_split('/(?=[A-Z]{1}[a-z]+)/', $modelName, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            $name = strtolower(implode(' ',  $matches));
            $result[] = $name;
        } 
        return $result;
    }

    /**
     * Used to generate the permissions for a model and insert it to database
     * @param string $model
     */
    private function generatePermissions($model) {

        //get prefix value from config file
        $prefix = Config::get("permissionsgenerator.prefix");        

        //get permissions values from config file
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