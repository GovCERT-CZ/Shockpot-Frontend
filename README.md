# Shockpot-Frontend
===========

Shockpot-Frontend is a full featured script to visualize statistics from a Shockpot honeypot.

It requires version of shockpot that logs to postgresql (https://github.com/GovCERT-CZ/shockpot).

It is based on Kippo-Graph (https://github.com/ikoniaris/kippo-graph). Thanks to ikoniaris.

It uses the Libchart PHP chart drawing library by Jean-Marc Trémeaux,
QGoogleVisualizationAPI PHP Wrapper for Google's Visualization API by Thomas Schäfer,
RedBeanPHP library by Gabor de Mooij, MaxMind and geoPlugin geolocation technology.

# REQUIREMENTS:
-------------
1. PHP version 5.3.4 or higher.
2. The following packages: _libapache2-mod-php5_, _php5-pgsql_, _php5-gd_, _php5-curl_.

On Ubuntu/Debian:
> apt-get update && apt-get install -y libapache2-mod-php5 php5-pgsql php5-gd php5-curl
>
> /etc/init.d/apache2 restart

# QUICK INSTALLATION:
-------------------
> wget https://github.com/GovCERT-CZ/Shockpot-Frontend/archive/master.zip
>
> mv Shockpot-Frontend-master.zip /var/www/html
>
> cd /var/www/html
>
> unzip Shockpot-Frontend-master.zip
>
> mv Shockpot-Frontend-master shockpot-frontend
>
> cd shockpot-frontend
>
> chmod 777 generated-graphs
>
> cp config.php.dist config.php
>
> nano config.php #enter the appropriate values

Browse to http://your-server/shockpot-frontend to generate the statistics.



