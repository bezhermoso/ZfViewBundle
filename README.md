ZfViewBundle
============

Use the Zend\View multi-step layout paradigm within your Symfony2 project.

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
                     * and thus can be rendered like below:
                     */
                    echo $this->content;
                    
                ?>
            </div>
        </body>
    </html>
```

Templates can also be defined within the `@Rendering` annoations in methods and will take precedence:

```php
    <?php

        /**
         * @Rendering("FooBundle:Some:other.html.phtml", template="FooBundle::secondary-layout.html.phtml")
        public function otherAction($name)
        {
            return array(
                'name' => $name,
            );
        }
```

You can also set `template` to `null` to keep the renderer from using one if a template is defined at the class level.
