Models
======

[ALWAYS STORE DATES IN DATABASE AS UTC!](https://medium.com/@kylekatarnls/always-use-utc-dates-and-times-8a8200ca3164)

It is [Base Model class](../src/Models/Model.php) with overloaded converting functions.
So extend your models from it.

Traits
------

Additionally [Base Model class](../src/Models/Model.php) use some helpful traits.

- [ModelLabels](../src/Traits/ModelLabels.php).
This trait gives labels to model attributes.

```php
// Client model

/**
 * {@inheritDoc}
 */
public static function attributeLabels(): array
{
    return [
        'name' => trans('Client\'s Name'),
        'created_at' => trans('Creation Date'),
        'updated_at' => trans('Modification Date'),
    ];
} 
```

```php
$client_name_label = Client::getAttributeLabel('name');
```

- [ModelScopes](../src/Traits/ModelScopes.php).
This trait contains [query scopes](https://laravel.com/docs/6.x/eloquent#local-scopes).

---

[Table of contents](./index.md)
