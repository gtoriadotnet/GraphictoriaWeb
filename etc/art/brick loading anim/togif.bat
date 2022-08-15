REM : XlXi 2022
REM : Put this in the directory of your PNG export.
REM : ImageMagick is required.

magick convert -delay 333,10000 -loop 0 -alpha set -dispose previous *.png ani.gif