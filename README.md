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

###Defining Templates and Layouts

Define the template and the layout to use using the `@Bez\ZfViewBundle\Configuration\Rendering` annotation.

```php
<?php

namespace FooBundle\Controller;

use Bez\ZfViewBundle\Configuration\Rendering;

/**
 *
 * @Rendering(layout="FooBundle::layout.phtml")
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

Child views -- in this case, `src/FooBundle/Resources/views/Some/some.phtml` -- are evaluated before the parent layouts. 
The output is captured to the `$this->content` variable and thus can be injected into the layout as follows:

In `src/FooBundle/Resources/layout.phtml`

```php
<html>
    <head>...</head>
    <body>
        <div class="container">
            <?php
                
                echo $this->content;
                
            ?>
        </div>
    </body>
</html>
```

The fact that child views are evaluated first is very significant. This allows child views to modify elements (i.e., stylesheets, scripts, menus, etc.) in the parent layout by means of [view helpers](Resources/docs/view-helpers.md).

###Overriding rendering options

Layouts can also be defined within the `@Rendering` annotation in a method and will take precedence:

```php
<?php

    /**
     * @Rendering("FooBundle:Some:other.phtml", layout="FooBundle::secondary.phtml")
     */
    public function otherAction($name)
    {
        return array(
            'name' => $name,
        );
    }
```

You can also set `layout` to `"none"` to disable a layout if one is defined at the class level. This will simply render the template without wrapping it any layout:

```php
<?php

/**
 * @Rendering(layout="::base.phtml")
 */
class FooController
{
    /**
     * @Rendering("FooBundle:Foo:bar.phtml", layout="none")
     */
    public function barAction()
    {
        return array();
    }
}

```

###Template names

As you can see in the above examples, you can omit the `format` part in naming your `phtml` templates. That is, `FooBundle:Controller:template.phtml` is equivalent to `FooBundle:Controller:template.html.phtml`. 

The bundle will look for `bundle:controller:name.format.phtml` or `bundle:controller:name.phtml` and will use whichever exists. 

##Explicit view composition

Returning a pre-configured ViewModel is also possible:

```php

class DefaultController
{
    public function indexAction()
    {
        $content = new ViewModel();
        $content->setTemplate('FooBundle:Default:index.phtml')
                ->setCaptureTo('content'); //Default.

        $sideBar = new ViewModel();
        $sideBar->setTemplate('FooBundle::sidebar.phtml');
        $sideBar->setCaptureTo('sidebar'); //This child-view will be outputted via $this->sidebar in the parent view.

        $layout = new ViewModel();
        $layout->setTemplate('FooBundle::layout.phtml');
        $layout->addChild($content);
        $layout->addChild($sideBar);

        return $layout;
    }
}

##View Helpers

Learn more about view helpers here: [View helpers documentation](Resources/docs/view-helpers.md)

##Contributing

####**Contributions are very welcome!**

_No unit tests yet._
