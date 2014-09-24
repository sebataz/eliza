eliza
=====

### a kiss javascript/php set of APIs
eliza is a set of **API** meant to simplify the creation of **web applications**.

The concept is **kiss** (Keep It Simple Stupid), a feed class generates a **collection of items**, formatted and ready to be echoed.

### What she features
The project includes a **PHP library** and several **jQuery plugins**, the communication between the PHP and JavaScript layers uses **JSON**.

eliza responds to a request using a feed source which can be formatted in **PHP, JSON, XML, HTML**.

eliza already includes some basic feeds that you can use: GitHistory, Node (files), Image, Page.

Moreover, eliza has a simple **buffering and caching** system for your pages.

### How she works
Basically you need two files to work with eliza, a **source class (feed)** and a **html template or page**.

An example of feed:
```php
    class Item extends eliza\beta\Feed {
        public $Value = '';
        
        public static function Feed {

            $Collection = new eliza\feed\JSONFeed();
            $Collection->add(new self(array('Hello World!');
            
            return $Collection
            
        }
    }
```

An example of page:
```php
    echo eliza\beta\Response::HTMLFeed('Item');
```
