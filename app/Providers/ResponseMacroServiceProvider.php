<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Response::macro('success', function ($response, $content = '', $status = 200) {
            return Response::json([
                'status' => 1,
                'response' => $response,
                'content' => $content,
            ], $status);
        });

        Response::macro('error', function ($response, $content = '', $status = 400) {
            return Response::json([
                'status' => 0,
                'response' => $response,
                'content' => $content,
            ], $status);
        });

        Response::macro('results', function ($value, $genre, $name) {
            switch ($value) {
                case 0:
                    return trans('messages.crud.' . $genre . 'RE', ['name' => $name[0]]);
                    break;
                case 1:
                    return trans('messages.crud.' . $genre . 'RSU', ['name' => $name[0]]);
                    break;
                default:
                    return trans('messages.crud.' . $genre . 'RS', ['name' => $name[1], 'value' => $value]);
                    break;
            }
        });
    }

    public function register()
    {
    }
}