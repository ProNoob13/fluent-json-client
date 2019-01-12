# Fluent JSON client
A PHP library to fluently interact with JSON API's

## Example usage
```php
<?php
    $API = new \PN13\FluentJSON\API('http://localhost/api');
    
    // http://localhost/api/company/contact
    $request = $API->company->contact;
    $request->post(['name' => 'John Doe', 'email' => 'example@example.com']);
```