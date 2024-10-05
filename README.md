# pluggable
Pluggable employs a modular approach, breaking down the application into components known as plugins. Each plugin is designed to be isolated, containing the necessary resources it needs to function independently.
Pluggable is best suited for API Development.

### How it works
1. **Installation**: When a plugin is installed, it is copied into the `plugins` directory.
2. **Configuration**: Plugins can be configured via the `config/plugins.php` file.
3. **Autoloading**: Plugins are automatically loaded when the application boots, ensuring that they are always available for use.
4. **Dependencies**: Plugins can depend on other plugins, ensuring that they are always installed and available.
5. **Isolation**: Plugins are isolated from each other, allowing them to be developed and maintained independently. Plugin can communicate with other plugins using service container and event dispatcher.
6. **Resources**: Plugins include all the resources they need to operate effectively. This includes backend logic, database models, API routes. etc.

## Getting Started
### Installation
1. Clone the repository: `git clone https://github.com/Bevynile/cohub_backend.git`
2. Install the dependencies: `composer install` or update `composer update`
3. configure the database: `cp .env.example .env` and update the `.env` file with your database credentials.
4. Run the migrations: `php artisan migrate`
5. Run the seeders: `php artisan db:seed` if any
6. Start the server: `php artisan serve`

When you create a plugin, it creates a service provider which is responsible for bootstrapping the plugin. The service provider registers the plugin's routes, controllers, and other resources. API Routes are injected to the api.php automatically.

### Configuration
Plugins can have their own configuration files, which can be stored in the `config/plugin.php` directory of the plugin.

You can register your plugin middleware and other configuration in `config/plugin.php` file.
you can use the middleware with the help of the `middleware` helper function.
`middleware(pluginName, key)`

### Usage

1. To view list of plugins, run `php artisan plugin:list`
2. To install a plugin, run `php artisan plugin:install <plugin-name>`
3. To uninstall a plugin, run `php artisan plugin:uninstall <plugin-name>`
4. To enable a plugin, run `php artisan plugin:enable <plugin-name>`
5. To disable a plugin, run `php artisan plugin:disable <plugin-name>`
6. To create a plugin, run `php artisan make:plugin <plugin-name> --description=description --type=api/web`
7. To create a plugin controller, run `php artisan plugin:controller <plugin-name> <controller-name>`
8. To create a plugin model, run `php artisan plugin:model <plugin-name> <model-name>`
9. To create a plugin migration, run `php artisan plugin:migration <plugin-name> <migration-name>`
10. To create a plugin service, run `php artisan plugin:service <plugin-name> <service-name>`
11. To create a plugin event, run `php artisan plugin:event <plugin-name> <event-name>` - When you create an event, it automatically creates a listener for it.
12. To create a plugin policy, run `php artisan plugin:policy <plugin-name> <policy-name>`
13. To create a plugin middleware, run `php artisan plugin:middleware <plugin-name> <middleware-name>`
14. To create a plugin job, run `php artisan plugin:job <plugin-name> <job-name>`


## API Endpoints
`/api/user/login`


## Authentication

Pluggable uses JWT for authentication. You can find more information about JWT in the [JWT documentation](https://jwt.io/).
But you can also change this in the config/auth.php file

## Data Models
- User: repesents a use in the system
- Projects: Represents a project with associated tasks, collaboators, and settings.
