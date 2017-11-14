# DTRT WP Weather

Displays historical weather information for the GPS location determined by the Featured Image.

## Setup

```
// 1. Install PHP dependencies
composer install

// 2. Install PHP and front-end dependencies which don't have composer.json files
bower install

// 3. Install Node dependencies into the parent plugin's folder
npm --prefix ./vendor/dotherightthing/wpdtrt-plugin/ install ./vendor/dotherightthing/wpdtrt-plugin/

// 4. Run the parent plugin's Gulp tasks against the contents of the child plugin's folder
// 5. Watch for changes to the child plugin's folder
gulp --gulpfile ./vendor/dotherightthing/wpdtrt-plugin/gulpfile.js --cwd ./
```

## Usage

Please read the [WordPress readme.txt](readme.txt) for usage instructions.