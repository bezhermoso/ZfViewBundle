#View Helpers

Just like Twig functions, view helpers provide you various functionalities available within your templates. For example, the `path` view helper is a direct equivalent to the `path` Twig function which is used to generate paths:

```php
    <a href="<?php echo $this->path('contact_us', array('lang' => 'en')) ?>">Contact Us</a>
```

##Built-in view helpers

###Routing helpers

```php
<?php
  
  //Generate paths
  echo $this->path('route_name', $parameters = array('param1' => 'value1'), $relative = false);
  
  //Generate URLs
  echo $this->url('route_name', $parameters = array('param2' => 2), $relative = false);

```

The above helpers work exactly like their Twig counterparts, `{{ path(...) }}` and `{{ url(...) }}`.

###Asset helpers

```php
<img src="<?php echo $this->asset('img/nyancat.png');" />

<?php
    $this->headScript()->appendFile($this->asset('js/nyan-cat-plugin.js'));
?>
```

###Authentication/authorization helpers

```php
<?php
    
    //Retrieve authenticated user. Returns null if authentication is absent in security context.
    $currentUser = $this->security()->user();
    
    // Query authorization layer.
    if ($this->security()->isGranted('ROLE_ADMIN', $object, $field = 1)) {
        /* AUTHORIZED! */
    }
```

###Translation helpers
```php
    
    //Straight-forward string translation. Only first argument -- the string key -- is required.
    
    echo $this->trans('message.to.translate', $arguments = array(), $domain = 'FooBundle', $locale = 'fr_FR');
    
    //Translation with pluralization. Only first and second argument -- the string key and count -- are required.
    echo $this->trans()
              ->choice(
                    '{0} There is no apples|{1} There is one apple|]1,Inf[ There are %count% apples',
                    $count = 10,
                    $arguments = array(),
                    $domain = 'messages',
                    $locale = 'en_US',
              );
    
```

###Controller renderer helper

```php
<?php

    echo $this->controller(
                    "FooBundle:bar:baz",
                    $controllerParams = array("id" => $id)
                )->render();
    
```

Above usage's Twig equivalent is:

```twig
    {{ render(controller('FooBundle:bar:baz', {id: id})) }}
```

###Request helper

Exposes the `Request` object.

```php
<?php

    $this->request();
```

###Session helper

This simply proxies `Request#getSession`.

```php
<?php
    
    $this->session()->get('foo');
    $this->session()->has('bar');
    $this->session()->all();
    
    foreach ($this->session()->getFlashBag()->get('error', array()) as $error) {
        echo '<div class="sys-error">' . $error . '</div>';
    }
    
```

###Kernel helper

Can be used to query the environment and debug variables.

```php
<?php
    
    $this->kernel()->env();
    $this->kernel()->debug();
    
```

###Form helpers

_These form helpers simply re-uses the form rendering engine from Symfony's built-in PHP templating and exposes them as view helpers._

```php
    <?php echo $this->form()->start($form); ?>
    <div class="errors">
        <?php echo $this->form()->errors($form); ?>
    </div>
    <div>
        <?php
            echo $this->form()->label($form['first_name']);
            echo $this->form()->errors($form['first_name']);
            echo $this->form()->widget($form['first_name']);
        ?>
    </div>
    <?php
        echo $this->form()->row($form['last_name']);
        
        echo $this->form()->csrf($form);
        
        echo $this->form()->rest($form);
        
        echo $this->form()->end($form);
    ?>
    
```

###Assetic helpers

_Assetic not fully supported yet._


###Escaping helpers

```php
<?php

    $this->escapeHtml("<script>alert(\"For the lulz!\");</script>");
    
    $escapedJs = $this->escapeJs($possiblyUnescapedJs);
    
    $escapedCss = $this->escapeCss($possiblyUnescapedCss);
    
    $escapedUrl = $this->escapeUrl($possiblyUnescapedUrl);
    
```

###Other available view helpers which are bundled along with `Zend/View`

 - [Doctype](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.doctype.html#zend-view-helpers-initial-doctype)
 - [HeadLink](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.head-link.html#zend-view-helpers-initial-headlink)
 - [HeadScript](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.head-script.html#zend-view-helpers-initial-headscript)
 - [InlineScript](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.inline-script.html#zend-view-helpers-initial-inlinescript)
 - [HeadMeta](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.head-meta.html#zend-view-helpers-initial-headmeta)
 - [Partial](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.html#partial-helper)
 - [PartialLoop](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.html#partial-helper)
 - [Placeholder](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.placeholder.html)
 - [Cycle](http://framework.zend.com/manual/2.0/en/modules/zend.view.helpers.html#cycle-helper)

##Writing your own view helper...

Simply extend `Zend\View\Helper\AbstractHelper` or implement `Zend\View\Helper\HelperInterface`, define it as a service tagged with `view_helper`.

Example:

```yaml

services:
    foobundle.utils.mailto_formatter:
        class: FooBundle\Model\MailToFormatter
        tags:
            - { name: view_helper, alias: mailTo }

```

Note the `alias` attribute. In the above example, you can invoke your view helper via this call:

```php
    echo $this->mailTo("me@mydomain.com", array('subject' => 'Hello!'));
```

The `alias` attribute can be omitted. In such cases, the alias is derived from the class name, i.e., `FooBundle\ViewHelper\GravatarPic` will be aliased with `gravatarPic`. 

If the `__invoke` method is defined in your view helper, arguments are passed to it as-is. Otherwise, the view helper instance is returned instead.
