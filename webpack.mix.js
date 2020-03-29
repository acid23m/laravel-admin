const mix = require('laravel-mix');

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/@fortawesome/fontawesome-free/css/all.css',
    'resources/css/src/bootadmin.css',
    'node_modules/daterangepicker/daterangepicker.css',
    'node_modules/select2/dist/css/select2.css',
    'node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css',
    'node_modules/suneditor/dist/css/suneditor.min.css',
    'resources/css/src/style.css'
], 'resources/css/dist/admin.all.css');

mix.scripts([
    'node_modules/jquery/dist/jquery.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
    'resources/js/src/bootadmin.js',
    'node_modules/bootbox/bootbox.all.js',
    'node_modules/moment/min/moment-with-locales.js',
    'node_modules/daterangepicker/daterangepicker.js',
    'node_modules/select2/dist/js/select2.js',
    'node_modules/html5sortable/dist/html5sortable.js',
    'node_modules/suneditor/dist/suneditor.min.js',
    'node_modules/suneditor/src/lang/en.js',
    'node_modules/suneditor/src/lang/ru.js',
    'resources/js/src/script.js'
], 'resources/js/dist/admin.all.js');

// mix.copyDirectory('node_modules/suneditor/dist/fonts', 'resources/css/fonts');
