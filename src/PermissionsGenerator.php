<?php

namespace ZakariaJawas\PermissionsGenerator;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PermissionsGenerator
{
    /**
     * Collected generated permissions rows to return for display
     *
     * @var array
     */
    private $generated = [];

    /**
     * Generate permissions for your project models
     */
    public function generate()
    {

        //check if permissions table exists
        if (!Schema::hasTable('permissions')) {

            $this->error("Please install Spatie permissions package first, and run php artisan migrate then run permissions:generate command");
            return;
        } //end if

        //get project models
        $models = $this->getModels();

        //check if project conains any models
        if (count($models) == 0) {

            $this->warn(
                "You don't have any models yet, create models then run the command again"
            );
            return;
        } //end if


        //process models => extract models names and convert it it permission style name
        $processedModels = $this->processModels($models);

        foreach ($processedModels as $model) {

            $this->generatePermissions($model);
        } //end for each


        $staticQuery = [];
        $staticPermissions = Config::get('permissionsgenerator.staticPermissions');
        foreach ($staticPermissions as $permission) {

            $staticQuery[] = ['name' => $permission, 'guard_name' => 'web'];
        } //end foreach

        // only insert static permissions that don't already exist
        $staticNames = array_map(function ($r) {
            return $r['name'];
        }, $staticQuery);

        if (count($staticNames) > 0) {
            $existingStatic = DB::table('permissions')->whereIn('name', $staticNames)->pluck('name')->toArray();

            $staticToInsert = array_values(array_filter($staticQuery, function ($r) use ($existingStatic) {
                return !in_array($r['name'], $existingStatic);
            }));

            if (count($staticToInsert) > 0) {
                $this->insertPermissions($staticToInsert);
                foreach ($staticToInsert as $row) {
                    $this->generated[] = $row;
                }
            }
        }

        // return generated rows for display in command
        return $this->generated;
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

                $class = sprintf(
                    '\%s%s',
                    Container::getInstance()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );

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
    private function processModels(Collection $models)
    {

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
    private function generatePermissions($model)
    {

        //get prefix value from config file
        $prefix = Config::get("permissionsgenerator.prefix");

        //get permissions values from config file
        $permissions = Config::get("permissionsgenerator.permissions");

        $query = [];
        foreach ($permissions as $permission) {

            if (empty($prefix)) {

                $row = ['name' => "$permission $model", 'guard_name' => 'web'];
            } else {

                $row = ['name' => "$prefix $permission $model", 'guard_name' => 'web'];
            } //end if

            $query[] = $row;
        } //end for

        // only insert rows that don't already exist
        $names = array_map(function ($r) {
            return $r['name'];
        }, $query);

        if (count($names) > 0) {
            $existing = DB::table('permissions')->whereIn('name', $names)->pluck('name')->toArray();

            $toInsert = array_values(array_filter($query, function ($r) use ($existing) {
                return !in_array($r['name'], $existing);
            }));

            if (count($toInsert) > 0) {
                $this->insertPermissions($toInsert);
                foreach ($toInsert as $row) {
                    $this->generated[] = $row;
                }
            }
        }
    }

    private function insertPermissions($query)
    {

        DB::table('permissions')->insertOrIgnore($query);
    }
}
