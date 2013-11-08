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
        @Rendering("FooBundle:Some:some.html.phtml")
        public function someAction($name)
        {
            return array(
                'name' => $name,
            );
        }
    }
```

In src/FooBundle/Resources/views/Some/some.html.phtml:

    Hello <?php echo $this->name; ?>!

In src/FooBundle/Resources/layout.html.phtml

    <html>
        <head>...</head>
        <body>
            <div class="container">
                <?php
                    <!-- Child views will be rendered here. -->
                    echo $this->content;
                ?>
            </div>
        </body>
    </html>
