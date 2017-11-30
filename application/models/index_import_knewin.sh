#!/bin/bash

php /app/application/models/index_import_knewin_tv.php >> /var/log/index_import_knewin_tv.log
php /app/application/models/index_import_knewin_radio.php >> /var/log/index_import_knewin_radio.log
