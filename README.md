# JSConfig

Usage:

```php
// Send an array of things to the frontend using JSConfig
JSConfig::add('name_of_json_key', MyObject::get()->toArray());
```

```js
// Retrieve the inserted data, and use an imaginary template renderer to output some HTML based on that data set.
templateRenderer
    .setTemplate('MyObjectList')
    .process(JSCONFIG['name_of_json_key']);
```
