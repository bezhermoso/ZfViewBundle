ZfViewBundle
============

Use the Zend\View multi-step layout paradigm within your Symfony2 project.

##Installation

You can install this bundle via Composer:

`php composer.php require bez/zfview-bundle:dev-master`

#Usage

###Activation

First of all add `"zf_view"` to the templating engines in your `config.yml`:

```yml
framework:
    templating:
        engines: [ 'twig', 'zf_view' ]
```

###Templates

Define templates using the `@Bzl\Bundle\ZfViewBundle\Configuration\Rendering` annotation.

```php
<?php

namespace FooBundle\Controller;

use Bzl\Bundle\ZfViewBundle\Configuration\Rendering;

/**
 *
 * @Rendering(template="FooBundle::layout.html.phtml")
 */
class SomeController extends Controller
{
    /**
     * @Rendering("FooBundle:Some:some.html.phtml")
     */
    public function someAction($name)
    {
        return array(
            'name' => $name,
        );
    }
}
```

In `src/FooBundle/Resources/views/Some/some.html.phtml`:

```php
Hello <?php echo $this->name; ?>!
```

In `src/FooBundle/Resources/layout.html.phtml`

```php
<html>
    <head>...</head>
    <body>
        <div class="container">
            <?php
                
                /* 
                 * Child views will be assigned the the content variable by default 
                 * and thus can be rendered like so:
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
     * @Rendering("FooBundle:Some:other.html.phtml", template="FooBundle::secondary-layout.html.phtml")
     */
    public function otherAction($name)
    {
        return array(
            'name' => $name,
        );
    }
```

You can also set `template` to `null` to disable a template if one is defined at the class level:

```php
<?php

/**
 * @Rendering(template="::base.html.phtml")
 */
class FooController
{
    /**
     * @Rendering("FooBundle:Foo:bar.html.phtml", template=null)
     */
    public function barAction()
    {
        return array();
    }
}

```

##View Helpers Documentation

Learn more about view helpers here: [View helpers documentation](Resources/docs/view-helpers.md)
