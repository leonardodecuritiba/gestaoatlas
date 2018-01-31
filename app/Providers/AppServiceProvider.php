<?php

namespace App\Providers;

use App\Kit;
use App\Models\Budgets\Budget;
use App\Models\Budgets\BudgetKit;
use App\Models\Budgets\BudgetPart;
use App\Models\Budgets\BudgetService;
use App\Models\Inputs\Pattern;
use App\Models\Inputs\Stocks\PatternStock;
use App\Models\Inputs\Stocks\ToolStock;
use App\Models\Inputs\Tool;
use App\Models\Inputs\Voids\VoidPattern;
use App\Models\Inputs\Voids\VoidTool;
use App\Models\Inputs\Voids\Voidx;
use App\Observers\Budget\BudgetObserver;
use App\Observers\Budget\BudgetKitObserver;
use App\Observers\Budget\BudgetPartObserver;
use App\Observers\Budget\BudgetServiceObserver;
use App\Observers\KitsObserver;
use App\Observers\PatternsObserver;
use App\Observers\PatternStocksObserver;
use App\Observers\PecasObserver;
use App\Observers\ServicosObserver;
use App\Observers\ToolsObserver;
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
use Faker\Generator as FakerGenerator;
use Faker\Factory as FakerFactory;

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
	    Tool::observe( ToolsObserver::class );
	    VoidTool::observe( VoidToolsObserver::class );
	    ToolStock::observe( ToolStocksObserver::class );
	    //patterns
	    Pattern::observe( PatternsObserver::class );
	    VoidPattern::observe( VoidPatternsObserver::class );
	    PatternStock::observe( PatternStocksObserver::class );

	    // =====================================================================
	    // ======================== BUDGETS ====================================
	    // =====================================================================

	    Budget::observe( BudgetObserver::class );
	    BudgetPart::observe( BudgetPartObserver::class );
	    BudgetService::observe( BudgetServiceObserver::class );
	    BudgetKit::observe( BudgetKitObserver::class );

	    // =====================================================================
	    // =====================================================================
	    // =====================================================================

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

	    $this->app->singleton( FakerGenerator::class, function () {
		    return FakerFactory::create( 'pt_BR' );
	    } );
    }
}
