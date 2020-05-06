#!/bin/bash

# Compile a minified version of the javascript, using the Google closure compiler, 
# then deploy site to production server

echo "Compiling 'counto12.min.js'..."
java -jar /usr/local/lib/closure-compiler-v20130823/compiler.jar --externs /usr/local/lib/closure-compiler-v20130823/externs/jquery-1.9.js --warning_level VERBOSE --js ../website/www/javascript/counto12.js --js_output_file ../website/www/javascript/counto12.min.js

echo "Deploy website..."
rsync -auv --safe-links --exclude-from="syncexclude.txt" --delete ../website/* user@host.site.net:counto12
