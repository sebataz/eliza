
                                     eliza
                        a kiss javascript/php set of APIs
                        ---------------------------------
                     
eliza is a set of API designed to simplify the storage of information for web
sites and applications. The concept is **kiss** (Keep It Simple Stupid!) feed 
classes define how information is stored, which eliza uses to create 
collections of feeds ready to be echoed.


What she features
-----------------

# eliza organizes feeds into **collections**.
# eliza can handle feed requests addressed to the **service.php** file; 
  the output can be configured to be either JSON or XML. 
# **ElizaService.js** implements all the asynchronous calls to service.php. 
# eliza makes use of **pseudo-tags** in order to directly render collections 
  into HTML. 
# eliza implements a basic check for privileges based on **roles and 
  permissions**, configurable in the global config.php.
# eliza has a simple **buffering and caching** system for your pages.
# eliza already provides some types of feed that you can extend: Node, File, 
  Image, XMLDocument.


How she works
-------------

Basically, all you need to work with eliza are two files, a **feed class** and 
a **html template or page** (and some stored information, of course).

An example of feed:
```php
    class AFeed extends eliza\XMLDocument {
        public $AValue;
    }
```

An example of page:
```php
    <?php include 'eliza.php'; ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
        </head>
        <body>
            [AFeed /]
        </body>
    </html>
```


Documentation
-------------

The most recent documentation can be found at https://sebataz.ch/api/eliza/docs/


eliza was coded by
------------------

sebataz <sebastien.rigoni@gmail.com>