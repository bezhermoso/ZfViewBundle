ZfViewBundle
============

Brings the Zend\View templating paradigm of composite views and view helpers within your Symfony2 project.

##Installation

You can install this bundle via Composer:

`php composer.phar require bez/zfview-bundle:dev-master`

Register the bundle in `app/AppKernel.php`:

```php
<?php
class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            /* ... */
            new Bez\ZfViewBundle\ZfViewBundle(),
        );
        
        /* ... */

        return $bundles;
    }
    
```

Add `"zf_view"` to the templating engines in your `config.yml`:

```yml
framework:
    templating:
        engines: [ 'twig', 'zf_view' ]
```

###Multi-step Layouts

Define templates using the `@Bez\ZfViewBundle\Configuration\Rendering` annotation.

```php
<?php

namespace FooBundle\Controller;

use Bez\ZfViewBundle\Configuration\Rendering;

/**
 *
 * @Rendering(template="FooBundle::layout.phtml")
 */
class SomeController extends Controller
{
    /**
     * @Rendering("FooBundle:Some:some.phtml")
     */
    public function someAction($name)
    {
        return array(
            'name' => $name,
        );
    }
    
    /**
     * @Rendering()
     * 
     * The template name will guessed if none is specified. 
     * In this example, FooBundle:Some:baz.phtml will be used.
     */
    public function bazAction()
    {
        return array();
    }
}
```

In `src/FooBundle/Resources/views/Some/some.phtml`:

```php
Hello <?php echo $this->name; ?>!
```

In `src/FooBundle/Resources/layout.phtml`

```php
<html>
    <head>...</head>
    <body>
        <div class="container">
            <?php
                
                /* 
                 * Child views will be assigned to the $content variable by default
                 * and can be accessed like so:
                 */
                echo $this->content;
                
            ?>
        </div>
    </body>
</html>
```

Templates can also be defined within the `@Rendering` annotation in a method and will take precedence:

```php
<?php

    /**
     * @Rendering("FooBundle:Some:other.phtml", template="FooBundle::secondary.phtml")
     */
    public function otherAction($name)
    {
        return array(
            'name' => $name,
        );
    }
```

You can also set `template` to `"none"` to disable a template if one is defined at the class level:

```php
<?php

/**
 * @Rendering(template="::base.phtml")
 */
class FooController
{
    /**
     * @Rendering("FooBundle:Foo:bar.phtml", template="none")
     */
    public function barAction()
    {
        return array();
    }
}

```

###Template names

As you can see in the above examples, you can omit the `template` portion in naming your `phtml` templates. That is, `FooBundle:Controller:template.phtml` is equivalent to `FooBundle:Controller:template.html.phtml`. 

##View Helpers

Learn more about view helpers here: [View helpers documentation](Resources/docs/view-helpers.md)

##Contributing

####**Contributions are very welcome!**

_No unit tests yet._
