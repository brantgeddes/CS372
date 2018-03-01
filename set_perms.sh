#!/bin/bash
chmod 711 ../public_html
chmod 711 views
chmod 711 controllers
chmod 711 routes

chmod 644 *.html
chmod 644 *.css
chmod 644 *.ico
chmod 644 *.php

chmod 644 views/*.html
chmod 644 views/*.css

chmod 644 controllers/*.js

chmod 644 routes/*.php

echo "Finished Setting Permissions"