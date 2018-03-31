# _Optimg

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

Example:

```
define( 'JPEGOPTIM_PATH', '/usr/local/bin/jpegoptim' );
```