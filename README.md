# Laravel / Eloquent Geonames

This package provides Eloquent models and Artisan commands to import/update a local copy of [GeoNames](http://www.geonames.org/) databases.

It aims to support local data + GeoNames API support.

**Notice:** This package is currently in beta status and some structures may change in future. It is not recommended to use in production systems but you are welcome to test it and suggest improvements. The pull requests should abide to good coding standards otherwise they will be rejected.

## Installation

Please include the following require in your composer.json :

	{
	    "require": {
	        "yurtesen/geonames": "dev-master"
	    }
	}

or install using command line :

	composer require yurtesen/geonames

## Artisan Commands

**Install**
Installs the migrations files to App migrations folder. Remember to execute the migrations afterwards.

	geonames:install

**Download**
  Downloads the GeoNames database files
  
	geonames:download [options]
		--update          Updates the downloaded files to latest versions

**Seed**
Seeds the database tables using downloaded files 

	geonames:seed [options]
		--update-files    Updates the downloaded files to latest versions
		--refresh         Truncate tables before importing
		--table=TABLE     Import only the given table    

## Configuration
The configuration has few options so far.

| Option     | Default                        | Description                        |
|------------|--------------------------------|------------------------------------|
|keepTxt     |true                            |Do NOT delete extracted .txt files  |
|storagePath |storage_path().'/meta/geonames' |Storage path for ownloaded files    |
|ignoreTables|array('geonames_alternate_names)|Do NOT import tables in this array()|

## Provided Eloquent Models
Relations currently need some work still and subject to change.

| Name                | Key       |Relations              |
|---------------------|-----------|-----------------------|
|GeonamesGeoname      |geoname_id |alternateName, timeZone|
|GeonamesAlternateName|geoname_id |geoname                |
|GeonamesCountryInfo  |iso        |timezone,continent     |
|GeonamesFeatureCode  |code       |                       |
|GeonamesLanguageCode |iso_639_3  |                       |
|GeonamesTimezone     |timezone_id|                       |
|GeonamesHierarchy    |parent_id  |                       |
|GeonamesAdmin1Code   |geoname_id |geoname,hierarchies    |
|GeonamesAdmin2Code   |geoname_id |geoname,hierarchies    |

## Tables
GeoNamess file names and corresponding table names created in your database.

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
