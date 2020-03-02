Laminas API Tools Doctrine Query Provider
=========================================

The Doctrine integration with Laminas API Tools includes Query Providers used to 
create filtering of data for each REST endpoint.  This may take into account
the current user's permissions and add filtering to a Doctrine QueryBuilder
object.  

Query Providers act much like an event listener but are hard-coded into a
Laminas API Tools Doctrine REST event.  

Writing your own Query Providers is not difficult but this library exists to
give you a jump start to writing custom Query Providers by supplying a 
plugin manager and abstract query providers for both ORM and ODM.  Included
are Query requets based tools for filtering and ordering request responses.
This filtering is provided by
[api-tools-doctrine-orm-querybuilder](https://github.com/laminas-api-tools/api-tools-doctrine-orm-querybuilder)
and 
[api-tools-doctrine-odm-querybuilder](https://github.com/laminas-api-tools/api-tools-doctrine-odm-querybuilder)


Requirements
------------
  
Please see the [composer.json](composer.json) file.


Installation
------------

Run the following `composer` command:

```console
$ composer require "laminas-api-tools/api-tools-doctrine-query-provider"
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-doctrine-query-provider": "^1.0"
}
```

And then run `composer update` to ensure the module is installed.

Finally, add the module name to your project's `config/application.config.php` under the `modules`
key:


```php
return [
    /* ... */
    'modules' => [
        /* ... */
        'Laminas\ApiTools\Doctrine\QueryBuilder',
    ],
    /* ... */
];
```


Configuration
-------------

Configuration is done inside the doctrine-connected section of the 
api-tools-doctrine configuration.  

```php
return [
    'zf-laminas-api-tools' => [
        'doctrine-connected' => [
            V1\Rest\PerformanceMerge\PerformanceMergeResource::class => [
                'query_providers' => [
  //                  'default' => Query\Provider\PerformanceMergeDefault::class,
                    'fetch' => Query\Provider\PerformanceMergeFetch::class,
                    'fetch_all' => Query\Provider\PerformanceMergeFetch::class,
                    'patch' => Query\Provider\PerformanceMergePatch::class,
                    'update' => Query\Provider\PerformanceMergeUpdate::class,
                    'delete' => Query\Provider\PerformanceMergeDelete::class,
                ],
            ],
```

Note patch_all, update_all, and delete_all do not exist.  For similar 
functionality to query providers for these actions use the provided events.
See [custom events](https://github.com/laminas-api-tools/api-tools-doctrine#custom-events).


What's in the box
-----------------

Included is filtering and sorting of REST responses and authentication through
the `Zend\Authentication\AuthenticationService` library.  This authentication
ties in directly with [api-tools-mvc-auth](https://github.com/laminas-api-tools/api-tools-mvc-auth).


Example
-------

```php
namespace DbApi\Query\Provider;

use ZF\Rest\ResourceEvent;
use ApiTools\Doctrine\Query\Provider\AbstractORMQueryProvider;
use Db\Fixture\RoleFixture;

final class PerformanceCorrectionPatch extends AbstractQueryProvider
{
    public function createQuery(ResourceEvent $event, $entityClass, $parameters)
    {
        $queryBuilder = parent::createQuery($event, $entityClass, $parameters);

        // Always allow admin
        if ($this->getAuthentication()->getIdentity()->getUser()->hasRole(RoleFixture::ADMIN)) {
            return $queryBuilder;
        }

        // Filter so the query provider only returns results belonging to this user
        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('row.user', ':user'))
            ->setParameter('user', $this->getAuthentication()->getIdentity()->getUser())
            ;

        return $queryBuilder;
    }
}
```
