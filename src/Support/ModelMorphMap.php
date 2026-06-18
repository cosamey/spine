<?php

namespace Mey\Spine\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ModelMorphMap
{
    /**
     * @param  string|array<int, string>|null  $paths
     * @return array<string, class-string<Model>>
     */
    public static function fromModels(string|array|null $paths = null, string $namespace = 'App\\Models\\'): array
    {
        $namespace = rtrim($namespace, '\\').'\\';

        /** @var array<string, class-string<Model>> $morphMap */
        $morphMap = collect((array) ($paths ?? app_path('Models')))
            ->filter(fn (string $path): bool => File::isDirectory($path))
            ->flatMap(fn (string $path): array => File::files($path))
            ->mapWithKeys(function (\SplFileInfo $file) use ($namespace): array {
                $fileName = $file->getBasename('.php');
                $modelClass = $namespace.$fileName;

                return is_subclass_of($modelClass, Model::class)
                    ? [str($fileName)->snake()->toString() => $modelClass]
                    : [];
            })
            ->all();

        return $morphMap;
    }
}
