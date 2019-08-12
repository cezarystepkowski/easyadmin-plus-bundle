# wingu Easy Admin Plus Bundle

This bundle provides some more functionality to [easycorp/easyadmin-bundle](https://github.com/EasyCorp/EasyAdminBundle).

Some of the features provided are:
- custom download action which allows exporting data into CSV format (search and filter mechanisms and batch operations are supported).
- possibility to define custom global actions that will be rendered on a list view (unlike [batch actions](https://symfony.com/doc/master/bundles/EasyAdminBundle/book/actions-configuration.html#batch-actions), global actions receive the same filters and search parameters as list action which allows to perform operations not only on selected items but on all entities at once).
- security controller integrating login page with [friendsofsymfony/user-bundle](https://github.com/FriendsOfSymfony/FOSUserBundle).
- extension for search autocomplete allowing to specify property that should be displayed in the results instead of relying on `__toString` method.
- autocomplete form type for [dynamic filters](https://symfony.com/doc/master/bundles/EasyAdminBundle/book/list-search-show-configuration.html#dynamic-filters-filters-option).
