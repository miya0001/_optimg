# _Optimg

[![Build Status](https://travis-ci.org/miya0001/_optimg.svg?branch=master)](https://travis-ci.org/miya0001/_optimg)

A WordPress plugin which optimize uploaded JPEGs and PNGs.

## Features

* JPEGs are optimized with the `jpegoptim`.
* PNGs are optimized with the `pngquant`.
* It optimize only resized images, original image is not optimized.

## Configuration

You can define constants which are path to commands.

| Constant | Default Value |
| -------------- |------------------- |
| JPEGOPTIM_PATH | /usr/bin/jpegoptim |
| PNGQUANT_PATH  | /usr/bin/pngquant  |

### Example:

```php
define( 'JPEGOPTIM_PATH', '/usr/local/bin/jpegoptim' );
define( 'PNGQUANT_PATH', '/usr/local/bin/pngquant' );
```

### Customizing

This plugin has filter hooks to customize the quality of images.

```php
// Change the JPEG quality.
add_filter( 'optimg_jpeg_quality', function( $quality ) {
    return 60; // It is the default.
} );

// Change the PNG quality.
add_filter( 'optimg_png_quality', function( $quality ) {
    return 1; // It is the default.
} );
```
