# Graft Framework

Graft is an little Framework for WordPress Plugins development.
It give you clean code architecture for OOP and MVC pattern in your Plugin.

# Why Graft ?

## For OOP

I am an developer who love OOP, so when i began WordPress Development i was frustrated that some Plugins don't use OOP.

Of course, I understand that some little Plugins don't have to use OOP, so this Framework is destinated for big Plugins with complex features.

## Make more Developer Friendly Plugins

During my experience of WordPress Developer, when i was using some Plugin i often asked sames questions :

- Which Hooks uses this Plugin ?
- Is this Plugin give some availables Filters ?
- Is it possible to override this Plugin's Templates ?


# Main Graft Features


## OOP

In an WordPress Plugin you do not have to make OOP Code (you can use only functions)
But with Graft, all things is only Object for better code quality.

## MVC Pattern and Template Overriding from Theme

Graft use the Twig Template Engine to allow you to doing MVC Pattern in your Plugin.
And Graft allow developers to override all Plugin's Template from their Theme (if you give permission)

## Annotations

In Graft, due to the OOP, all basics WordPress functions like `add_action('wp_head')` or `add_filter()` are Annotations like `@Action(name="wp_head")`, so you don't have to specify any callback, it use the specified Method.

## Container

All Plugins made with Graft have an Dependency Injection Container (PHP-DI Container) for using autowiring with your Classes or with Graft Injectable Components.

And the container have the list of all WordPress Components used by the Plugin (Action, Filter, Admin Page ...)