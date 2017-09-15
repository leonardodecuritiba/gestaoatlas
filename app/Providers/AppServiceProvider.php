<?php

namespace App\Providers;

use App\Kit;
use App\Models\Inputs\Stocks\PatternStock;
use App\Models\Inputs\Stocks\ToolStock;
use App\Models\Inputs\Voids\VoidPattern;
use App\Models\Inputs\Voids\VoidTool;
use App\Models\Inputs\Voids\Voidx;
use App\Observers\KitsObserver;
use App\Observers\PatternStocksObserver;
use App\Observers\PecasObserver;
use App\Observers\ServicosObserver;
use App\Observers\ToolStocksObserver;
use App\Observers\VoidPatternsObserver;
use App\Observers\VoidsObserver;
use App\Observers\VoidToolsObserver;
use App\Peca;
use App\Servico;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Carbon::setLocale($this->app->getLocale());
        Servico::observe(ServicosObserver::class);
        Peca::observe(PecasObserver::class);
        Kit::observe(KitsObserver::class);

	    Voidx::observe( VoidsObserver::class );

	    //tools
	    VoidTool::observe( VoidToolsObserver::class );
	    ToolStock::observe( ToolStocksObserver::class );
	    //patterns
	    VoidPattern::observe( VoidPatternsObserver::class );
	    PatternStock::observe( PatternStocksObserver::class );

        Validator::extend('unique_cliente', function ($attribute, $value, $parameters, $validator) {
            // Get the parameters passed to the rule
            // Check the table and return true only if there are no entries matching
            // both the first field name and the user input value as well as
            // the second field name and the second field value
            if (count($parameters) > 4) {
                list($table, $field, $field2, $field2Value, $field_not, $field_notValue) = $parameters;
                return DB::table($table)
                        ->where($field, $value)
                        ->where($field2, $field2Value)
                        ->where($field_not, '<>', $field_notValue)
                        ->count() == 0;
            } else {
                list($table, $field, $field2, $field2Value) = $parameters;
                return DB::table($table)
                        ->where($field, $value)
                        ->where($field2, $field2Value)
                        ->count() == 0;

            }

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
