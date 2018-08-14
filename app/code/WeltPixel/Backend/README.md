# m2-weltpixel-backend

### Installation

With composer:

```sh
$ composer config repositories.welpixel-m2-weltpixel-backend git git@github.com:rusdragos/m2-weltpixel-backend.git
$ composer require weltpixel/m2-weltpixel-backend:dev-master
```

Manually:

Copy the zip into app/code/WeltPixel/Backend directory


#### After installation by either means, enable the extension by running following commands:

```sh
$ php bin/magento module:enable WeltPixel_Backend --clear-static-content
$ php bin/magento setup:upgrade
```
