# Service container
The service container implements PSR-11.

## Get Service
**A service is always called by it's fully qualified namespace.**  
When a service is called from `get($id)` method, the function will
verify first if the class exists. Then it will verify if this service has
been declared in a service configuration file.  
If the service declaration is found, it's configuration will be used.
(priority is given to lib/service config file) If not, the function will
try to get the service without configuration.  

## Service config files

`lib/service`

**lib/Service**  
Used for services dependencies declaration

### Services declaration
In service configuration file ,a service must be declared width it's
**fully qualified namespace**.

### Service arguments
The arguments are declared width the key: `argument`  

4 types of arguments can be passed to a service:
-  another service:  
in this case, use the service id width @ prefix.  
e.g. `@Router`

-  single value:  
simply declare the value.  
e.g. `"my single value"` or `1234`
   
-  config file:  
in this case, enclose the config path width braces  
e.g. `{lib/router}`
   
-  env var:  
in this case, use the following declaration:
`$env(MY_ENV_VAR)`
e.g. `$env(BASE_DIR)`

Json Exemple:

    // config/lib/service.json
    
    {
      "Lib\Router\Router": {
        "argument": [
          "@My\Service\Dependency",
          "my string var",
          152437,
          "{config/path}",
          "$env(BASE_DIR)"
        ]
      }
    }
    
    // script
    
    use Lib\Router\Router;
    $container = new Container();
    $router = $container->get(Router::class);