#!/bin/bash
chmod 711 ../public_html
chmod 711 views
chmod 711 controllers
chmod 711 routes
chmod 711 routes/classes
chmod 711 views/navbar-view
chmod 711 views/forms

chmod 644 *.html
chmod 644 *.css
chmod 644 *.ico
chmod 644 *.php

chmod 644 views/*.html
chmod 644 views/*.css

chmod 644 controllers/*.js

chmod 644 routes/*.php
chmod 644 routes/classes/*.php

chmod 644 views/navbar-view/*.html
chmod 644 views/navbar-view/*.css

chmod 644 views/forms/*.html

echo "Finished Setting Permissions"