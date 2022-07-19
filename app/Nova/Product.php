<?php

namespace App\Nova;

use App\Nova\Category;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'slug',
        'SKU',
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
            // SpatieMediaLibraryImageColumn::make('product-image')
            //     ->label('Image')
            //     ->collection('product-images'),
            // Files::make('Multiple files', 'car_images'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Markdown::make('Description')
                ->rules('required', 'max:255'),
            Slug::make('Slug')
                ->from('Name')
                ->rules('required', 'max:255')
                ->creationRules('unique:products,slug')
                ->updateRules('unique:products,slug,{{resourceId}}'),
            BelongsTo::make('Category', 'category', Category::class),
            Number::make('Price')
                ->sortable()
                ->min(1)
                ->step(0.01)
                ->rules('required', 'numeric', 'regex:/^\d{1,6}(\.\d{0,2})?$/'),
            Text::make('SKU')
                ->sortable()
                ->required()
                ->creationRules('unique:products,sku')
                ->updateRules('unique:products,sku,{{resourceId}}'),
            Number::make('Quantity')
                ->sortable()
                ->min(1)
                ->rules('required', 'numeric'),
            Boolean::make('Is visible')->sortable(),
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
        return [];
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
