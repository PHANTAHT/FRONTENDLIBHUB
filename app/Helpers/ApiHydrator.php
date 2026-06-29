<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ApiHydrator
{
    public static function hydrateSingle($class, $data)
    {
        if (empty($data)) return null;
        if (is_object($data)) $data = (array) $data;

        $model = new $class();
        
        $attributes = [];
        $relations = [];

        foreach ($data as $key => $value) {
            $camelKey = Str::camel($key);
            if (method_exists($model, $camelKey)) {
                $relations[$camelKey] = $value;
            } elseif (method_exists($model, $key)) {
                $relations[$key] = $value;
            } else {
                $attributes[$key] = $value;
            }
        }

        $model->forceFill($attributes);
        $model->exists = true;

        foreach ($relations as $key => $value) {
            try {
                $relationObject = $model->$key();
                
                if ($relationObject instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $relatedClass = get_class($relationObject->getRelated());
                    
                    $isMany = $relationObject instanceof \Illuminate\Database\Eloquent\Relations\HasMany 
                           || $relationObject instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany
                           || $relationObject instanceof \Illuminate\Database\Eloquent\Relations\HasManyThrough
                           || $relationObject instanceof \Illuminate\Database\Eloquent\Relations\MorphMany;
                    
                    if ($isMany) {
                        $hydratedRelated = self::hydrateCollection($relatedClass, $value);
                        $model->setRelation($key, $hydratedRelated);
                        $model->setRelation(Str::snake($key), $hydratedRelated);
                    } else {
                        $hydratedRelated = self::hydrateSingle($relatedClass, $value);
                        $model->setRelation($key, $hydratedRelated);
                        $model->setRelation(Str::snake($key), $hydratedRelated);
                    }
                } else {
                    $model->setAttribute($key, $value);
                }
            } catch (\Exception $e) {
                // If it fails to execute the method (e.g. not a relation), treat it as attribute
                $model->setAttribute($key, $value);
            }
        }

        return $model;
    }

    public static function hydrateCollection($class, $data)
    {
        if (empty($data)) return collect();
        if ($data instanceof Collection) return $data;

        $items = [];
        foreach ($data as $item) {
            $items[] = self::hydrateSingle($class, $item);
        }
        return collect($items);
    }

    public static function hydratePaginated($class, $paginatedData)
    {
        if (empty($paginatedData)) return null;

        // In Laravel, length aware paginators are returned as JSON with a 'data' key containing the items
        $items = $paginatedData['data'] ?? [];
        $hydratedItems = self::hydrateCollection($class, $items);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $hydratedItems,
            $paginatedData['total'] ?? count($hydratedItems),
            $paginatedData['per_page'] ?? 15,
            $paginatedData['current_page'] ?? 1,
            [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );

        return $paginator;
    }
}
