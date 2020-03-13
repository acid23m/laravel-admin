Widgets
=======

To visualize model data you can use special widgets.

ModelGrid
---------

Table of models with pagination, sorting and filtering features.

```php
// controller

/**
 * Displays a listing of the resource.
 *
 * @param Request $request
 * @return View
 */
public function index(Request $request): View
{
    $params = $request->query();
    
    $modelgrid_config = [
        'model_class' => Client::class,
        'collection' => Client::filter($params)->sortable()->paginate(),
        'columns' => [
            ['attribute' => 'id'],
            [
                'attribute' => 'name',
                'value' => static function (Client $item): string {
                    return "<strong>{$item->name}</strong>";
                },
            ],
        ],
    ];
    
    return view('admin.clients.index', \compact('modelgrid_config'));
}
```

```
// view

@modelGrid($modelgrid_config)
```

### Columns configuration

There are 3 possible formats for columns:

- array
- column class name
- column class instance

```php
// array

[
    /*
    simple column
    independent from resource
    without "attribute"
    */
    [
        'label' => 'Num',
        'value' => static function (Client $item, int $index): string {
            $number = $index + 1;
    
            return (string)$number;
        },
        'cell_width' => 60,
        'cell_class' => 'text-center text-muted',
    ],

    /*
    complex column
    */
    [
        'attribute' => 'description',
        'label' => 'Full Description', // tries get getAttributeLabel('description') if null
        'value' => static function (Client $item, int $index): string {
            return \nl2br($item->description);
        },
        'cell_width' => null,
        'cell_class' => null,
        'filter' => true,
    ],
],
```

```php
// class name

[
    IndexColumn::class,
    ActiveColumn::class,
    CreatedAtColumn::class,
],
```

```php
// instance

[
    new class extends AbstractDateColumn
    {
        protected string $attribute = 'pub_date';
    },
    new ActionColumn([
        'view' => static function (Client $item): string {
            return route('admin.clients.show', $item);
        },
        'edit' => static function (Client $item): string {
            return route('admin.clients.edit', $item);
        },
        'delete' => static function (Client $item) use ($auth): ?string {
            if (auth('admin')->user()->cant(Role::ADMIN)) {
                return null;
            }

            return route('admin.clients.destroy', $item);
        },
    ]),
],
```

Your own columns must extends `\SP\Admin\View\Widgets\ModelGrid\Columns\Column`
or `\SP\Admin\View\Widgets\ModelGrid\Columns\ModelColumn` class.
Override `boot()` method.
See predefined columns as examples in [Columns](../src/View/Widgets/ModelGrid/Columns) directory.

### Sorting

Sortable columns are provided by [kyslik/column-sortable](https://github.com/Kyslik/column-sortable) package.

Add `\Kyslik\ColumnSortable\Sortable` trait to the model.
Then define `$sortable` attribute.

```php
// model

/**
 * The attributes that should be sortable.
 *
 * @var array
 */
public array $sortable = [
    'name',
    'email',
    'active',
    'created_at',
];
```

Now `sortable` scope can be used which sort query result
according to uri parameters.

```php
Client::sortable()->paginate()
```

### Filtering

Define `filter` [query scope](https://laravel.com/docs/7.x/eloquent#local-scopes).

```php
// model

/**
 * Filters query.
 *
 * @param Builder $query
 * @param array $params Uri parameters
 * @return Builder
 */
public function scopeFilter(Builder $query, array $params = []): Builder
{
    $params = \array_map('trim', $params);

    if (isset($params['name']) && filled($params['name'])) {
        $query->where('name', 'like', "%{$params['name']}%");
    }

    if (isset($params['active']) && filled($params['active'])) {
        $is_active = $params['active'] === 'true';
        $query->where('active', '=', $is_active);
    }

    /** @var string $timezone */
    $timezone = config('app.timezone', 'UTC');
    $time_start = '00:00:00';
    $time_end = '23:59:59';

    if (isset($params['created_at']) && filled($params['created_at'])) {
        if (\strpos($params['created_at'], CreatedAtColumn::DATETIME_RANGE_SEPARATOR) !== false) {
            [$date_start, $date_end] = \explode(CreatedAtColumn::DATETIME_RANGE_SEPARATOR, $params['created_at']);
            $date_start = Carbon::parse("$date_start $time_start", $timezone)
                ->timezone('UTC')
                ->format(STANDARD_FORMAT__DATETIME);
            $date_end = Carbon::parse("$date_end $time_end", $timezone)
                ->timezone('UTC')
                ->format(STANDARD_FORMAT__DATETIME);
            $query->whereBetween('created_at', [$date_start, $date_end]);
        } else {
            $query->where('created_at', '=', $params['created_at']);
        }
    }

    return $query;
}
```

Now `filter` scope can be used which limit query result
according to uri parameters.

```php
$params = $request->query();
    
Client::filter($params)->paginate()
```

ModelDetails
------------

Details of one model.

```php
// controller

/**
 * Displays the specified resource.
 *
 * @param Client $client
 * @return View
 */
public function show(Client $client): View
{
    $modeldetails_config = [
        'model' => $client,
        'attributes' => [
            ['attribute' => 'id'],
            [
                'attribute' => 'name',
                'value' => static function (Client $item): string {
                    return "<strong>{$item->name}</strong>";
                },
            ],
        ],
    ];
    
    return view('admin.clients.show', [
        'model' => $client,
        'modeldetails_config' => $modeldetails_config,
    ]);
}
```

```
// view

@modelDetails($modeldetails_config)
```

### Rows configuration

There are 3 possible formats for rows:

- array
- row class name
- row class instance

```php
// array

[
    /*
    simple row
    independent from resource
    without "attribute"
    */
    [
        'attribute' => 'email',
        'value' => static function (Client $item): string {
            $value = '<a href="mailto:' . $item->email . '">';
            $value .= $item->email;
            $value .= '</span>';

            return $value;
        },
    ],

    /*
    complex row
    */
    [
        'attribute' => 'description',
        'label' => 'Full Description', // tries get getAttributeLabel('description') if null
        'value' => static function (Client $item, int $index): string {
            return \nl2br($item->description);
        },
    ],
],
```

```php
// class name

[
    ActiveRow::class,
    CreatedAtRow::class,
    UpdatedAtRow::class,
],
```

```php
// instance

[
    new ImageGalleryRow(['type' => 'space']),
    new class extends AbstractDateRow
    {
        protected string $attribute = 'pub_date';
    },
]
```

Your own rows must extends `\SP\Admin\View\Widgets\ModelDetails\Rows\Row`
or `\SP\Admin\View\Widgets\ModelDetails\Rows\ModelRow` class.
Override `boot()` method.
See predefined rows as examples in [Rows](../src/View/Widgets/ModelDetails/Rows) directory.

---

[Table of contents](./index.md)
