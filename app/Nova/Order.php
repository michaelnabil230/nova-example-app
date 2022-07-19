<?php

namespace App\Nova;

use App\Nova\Customer;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use App\Nova\Metrics\OpenOrders;
use App\Nova\Metrics\OrdersToday;
use Laravel\Nova\Fields\Markdown;
use App\Nova\Metrics\AveragePrice;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Number')
                ->default('OR-' . random_int(100000, 999999))
                ->readonly()
                // ->cre
                ->creationRules('unique:orders,number')
                ->updateRules('unique:orders,number,{{resourceId}}')
                ->sortable(),
            Markdown::make('Notes'),
            BelongsTo::make('Customer')->sortable(),
            Text::make('Total price')->sortable(),
            Text::make('shipping_price', 'Shipping cost')->sortable(),
            Date::make('created_at', 'Order Date')->hideWhenCreating()->hideWhenUpdating(),

            Select::make('Status')
                ->options([
                    'new' => 'New',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ])
                ->default('new')
                ->required()
                ->hideFromIndex(),

            Status::make('Status')
                ->loadingWhen(['processing'])
                ->failedWhen(['cancelled']),

            Select::make('Shipping method')
                ->options([
                    'free' => 'Free',
                    'flat' => 'Flat',
                    'flat_rate' => 'Flat rate',
                    'flat_rate_per_item' => 'Flat rate per item',
                ])
                ->default('free')
                ->required(),

            BelongsToMany::make('Products')->fields(function () {
                return [
                    Number::make('Quantity')
                        ->min(1)
                        ->rules('required', 'numeric'),
                ];
            }),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            OrdersToday::make(),
            OpenOrders::make(),
            AveragePrice::make(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
