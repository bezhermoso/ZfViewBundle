ZfViewBundle
============

Use the Zend\View multi-step layout paradigm within your Symfony2 project.

##Installation

You can install this bundle via Composer:

`php composer.php require bez/zfview-bundle:dev-master`

#Usage

###Activation

First of all Add `"zf_view"` to the templating engines in your `config.yml`:

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

##View Helpers

Just like Twig functions, view helpers provide you various functionalities available within your templates. For example, the `path` view helper is a direct equivalent to the `path` Twig function which is used to generate URLS:

```php
    <a href="<?php echo $this->path('contact_us', array('lang' => 'en')) ?>">Contact Us</a>
```

###Make your own view helper

Simply extend `Zend\View\Helper\AbstractHelper` or implement `Zend\View\Helper\HelperInterface`, define it as a service tagged with `zend.view_helper`.

Example:

```yaml

services:
    foobundle.utils.mailto_formatter:
        class: FooBundle\Model\MailToFormatter
        tags:
            - { name: zend.view_helper, alias: mailTo }

```

Note the `alias` attribute. This is required. In the above example, you can invoke your view helper via this call:

```php
    echo $this->mailTo("bezalelhermoso@gmail.com", array('subject' => 'Hello!'));
```

The parameters will be passed to the `__invoke` method of the view helper instance.
