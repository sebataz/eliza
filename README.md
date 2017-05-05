
eliza
=====
a kiss javascript/php set of APIs
---------------------------------
                     
eliza is a set of API designed to simplify the storage of information for web
sites and applications. The concept is __kiss__ (Keep It Simple Stupid!) feed 
classes define how information is stored, which eliza uses to create 
collections of feeds ready to be echoed.


What she features
-----------------

* eliza organizes feeds into __collections__.

* eliza can handle feed requests addressed to the __service.php__ file; 
  the output can be configured to be either JSON or XML. 
  
* __ElizaService.js__ implements all the asynchronous calls to service.php. 

* eliza makes use of __pseudo-tags__ in order to directly render collections 
  into HTML. 
  
* eliza implements a basic check for privileges based on __roles and 
  permissions__, configurable in the global config.php.
  
* eliza has a simple __buffering and caching__ system for your pages.

* eliza already provides some types of feed that you can extend: Node, File, 
  Image, XMLDocument.


How she works
-------------

Basically, all you need to work with eliza are two files, a __feed class__ and 
a __html template or page__ (and some stored information, of course).

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