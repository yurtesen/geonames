[![Total Downloads](https://poser.pugx.org/yurtesen/geonames/d/total.svg)](https://packagist.org/packages/yurtesen/geonames)
[![Latest Stable Version](https://poser.pugx.org/yurtesen/geonames/v/stable.svg)](https://packagist.org/packages/yurtesen/geonames)
[![Latest Unstable Version](https://poser.pugx.org/yurtesen/geonames/v/unstable.svg)](https://packagist.org/packages/yurtesen/geonames)
[![License](https://poser.pugx.org/yurtesen/geonames/license.svg)](https://packagist.org/packages/yurtesen/geonames)

# Laravel / Lumen / Eloquent Geonames

This package provides probably the best Eloquent models, most complete SQL schemas and fastest Artisan commands to import/update a local copy of [GeoNames](http://www.geonames.org/) databases.

## Installation

*Note:* If you are using Lumen. You have to first install [irazasyed/larasupport](https://github.com/irazasyed/larasupport) !

Please include the following require in your composer.json :

	{
	    "require": {
	        "yurtesen/geonames": "dev-master"
	    }
	}

or install using command line :

	composer require yurtesen/geonames:dev-master

after installation, you will need to add the service provider in your config\app.php to 'providers' array

	Yurtesen\Geonames\GeonamesServiceProvider::class,
	
or for Lumen add service provider in bootstrap\app.php
    
	$app->register(Yurtesen\Geonames\GeonamesServiceProvider::class);
	
## Usage and Configuration

Please see the [wiki](https://github.com/yurtesen/geonames/wiki) for further information

## Provided Eloquent Models

Please see the *Wiki* pages for implementation details.

| Name                | Key       |Relations                                  | Scopes                 |
|---------------------|-----------|-------------------------------------------|------------------------|
|GeonamesGeoname      |geoname_id |alternateName, timeZone,country            |admin1,city,countryInfo |
|GeonamesAlternateName|geoname_id |geoname                                    |                        |
|GeonamesCountryInfo  |iso        |timezone,continent                         |                        |
|GeonamesFeatureCode  |code       |                                           |                        |
|GeonamesLanguageCode |iso_639_3  |                                           |                        |
|GeonamesTimezone     |timezone_id|                                           |                        |
|GeonamesHierarchy    |parent_id  |                                           |                        |
|GeonamesAdmin1Code   |geoname_id |geoname,hierarchies                        |                        |
|GeonamesAdmin2Code   |geoname_id |geoname,hierarchies                        |                        |

## Tables
GeoNames file names and corresponding table names created in your database.

|Filename             |Tablename                |
|---------------------|-------------------------|
|timeZones.txt        |geonames_timezones       |
|allCountries.zip     |geonames_geonames        |
|countryInfo.txt      |geonames_country_infos   |
|iso-languagecodes.txt|geonames_language_codes  |
|alternateNames.zip   |geonames_alternate_names |
|hierarchy.zip        |geonames_hierarchies     |
|admin1CodesASCII.txt |geonames_admin1_codes    |
|admin2Codes.txt      |geonames_admin2_codes    |
|featureCodes_en.txt  |geonames_feature_codes   |

## If You Need Help
Please check the [wiki](https://github.com/yurtesen/geonames/wiki) for more information about how to utilize the package efficiently and usage examples. If something does not work or if you have a suggestion, please do not hesitate to use the issue tracker.
