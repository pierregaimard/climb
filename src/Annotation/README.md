# Annotation feature

This feature provides a system to declare annotations in docComment and 
retrieve it into php classes.

## Annotation Declaration 
_Rules to be observed when declaring an annotation in the docComment_
### Identification
The identification character of an annotation **MUST** be an `@`  
e.g. `@Route`

### Annotation name
The annotation name:
-   **MUST** start width uppercase.

-   Can only use alpha-numeric characters. `[a-zA-Z]`. It can't use special
    characters or white spaces.   
  
e.g. `@MyAnnotationName`  
_This option can be changed by changing the default annotation name declaration regex_

### Options
#### Option Declaration
When options are needed it **MUST** be declared between parentheses.  
e.g. `@Route(name="my_route")`  

#### Separator
Each option must be separated by a comma.  
`option1, option2`

#### Options types
Four types can be declared in an annotation:
<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Note</th>
            <th>Example</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>String</td>
            <td>Must be declared between double quotes</td>
            <td>"my string"</td>
        </tr>
        <tr>
            <td>Int</td>
            <td>Can be declared without double quotes</td>
            <td>1548</td>
        </tr>
        <tr>
            <td>Bool</td>
            <td>Declared width keywords <b>true</b> or <b>false</b></td>
            <td>store = false</td>
        </tr>
        <tr>
            <td>Array</td>
            <td>
                Must be daclared between braces.<br>
                <i>Arrays can be nested to infinity</i>
            </td>
            <td>
                { 1254, "Hello world" }<br>
                { "hello world", { 1254, "test" }, 1845}
            </td>
        </tr>
    </tbody>
</table>

#### Key
A key can be specified for any type of data, but it is optional.  
In this case, the affectation operator `=` must be used.  

e.g. `key = "my value"` or `myArray = { 1254, number = 545, "Hello world" }`  

#### Level
annotations can be declared at the class, method and attribute level.  

## Annotation classes 
Foreach annotation you **MUST** declare an annotation class who implements
**AnnotationInterface**.  

### Attributes
Every attribute used in annotation declaration **MUST** exist in the annotation class.  
Getter and Setter **MUST** be declared for each attribute too.

## Annotation configuration
This configuration file contains 2 sections.

### CONFIG section
This section contains 2 global annotation settings:
-   **ANNOTATION_NAME_VALID_REGEX**  
    This Regex defines the valid regex for annotation name used in annotation declaration.
    
-   **OPTION_KEY_VALID_REGEX**  
    This regex defines the valid regex for annotation option name used in annotation declaration.
  
        {
          "CONFIG": {
            "ANNOTATION_NAME_VALID_REGEX": "#^([A-Z][a-z]+)+([\\\\\\\\]?[A-Z][a-z]+)*$#",
            "OPTION_KEY_VALID_REGEX": "#^[a-zA-Z][a-zA-Z0-9]*(_?[a-zA-Z0-9]+)*$#"
          }
        }  
  
### ANNOTATIONS section
Each annotation **MUST** be declared in the configuration file
in the ANNOTATION section.  

The key **MUST** be the annotation **Tag** name.  
e.g. `Route` for `@Route` annotation declaration.  

A `class` key **MUST** be added to each annotation declaration to declare the
annotation class namespace.
        
    // Exemple for @Route width Climb\Router\Annotation\Route 
    
    {
      "ANNOTATIONS": {
        "Route": {
          "class": "Climb\\Router\\Annotation\\Route"
        }
      }
    }
    

## Annotation Reader
Reader class is used to retrieve annotations declarations from a given class docComment.  
It uses \ReflectionClass.  

This Service provides a few methods to retrieve annotations and returns
hydrated annotations objects.

## Full exemple

    Annotation declaration
    // Class AdminController
    
    class AdminController
    {
        /**
         * @Route(path="/admin/home", name="my_route")
         */
        public function home()
        {
        }
    }
    
    
    
    Configuration file
    // config/lib/annotation.json
    
    {
      "CONFIG": {
        "ANNOTATION_NAME_VALID_REGEX": "#^([A-Z][a-z]+)+([\\\\\\\\]?[A-Z][a-z]+)*$#",
        "ANNOTATION_OPTION_KEY_VALID_REGEX": "#^[a-zA-Z][a-zA-Z0-9]*(_?[a-zA-Z0-9]+)*$#"
      },
      "ANNOTATIONS": {
        "Route": {
          "class": "Climb\\Router\\Annotation\\Route"
        }
      }
    }  
    
    
    
    Annotation class
    // Class Route
    
    <?php
    
    namespace Annotation;
    
    class Route implements AnnotationInterface
    {
        /**
         * @var string
         */
        private string $path
        
        /**
         * @var string
         */
        private string $name
        
        /**
         * @return string
         */
        public function getPath(): string
        {
            return $this->path;
        }
        
        /**
         * @param string $path
         *
         * @return string
         */
        public function setPath($path): void
        {
            $this->path = $path;
        }
        
        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }
        
        /**
         * @param string $name
         *
         * @return string
         */
        public function setName($name): void
        {
            $this->name = $name;
        }
    } 
    
    
    Index.php
    
    use Annotation\Reader;
    
    $reader = new Reader(Controller::class);
    $annotation = $reader->getMethodAnnotation('home', 'Route');
    
    echo 'result' . $annotation->getPath(); 
    // result: /admin/home
    
    