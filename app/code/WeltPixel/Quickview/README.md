# m2-weltpixel-quickview

### Installation

Dependencies:
 - m2-weltpixel-backend

With composer:

```sh
$ composer config repositories.welpixel-m2-weltpixel-quickview git git@github.com:rusdragos/m2-weltpixel-quickview.git
$ composer require weltpixel/m2-weltpixel-quickview:dev-master
```

Manually:

Copy the zip into app/code/WeltPixel/Quickview directory


#### After installation by either means, enable the extension by running following commands:

```sh
$ php bin/magento module:enable WeltPixel_Quickview --clear-static-content
$ php bin/magento setup:upgrade
```
