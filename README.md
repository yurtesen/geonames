# Laravel / Eloquent Geonames

This package provides probably the best Eloquent models, most complete SQL schemas and fastest Artisan commands to import/update a local copy of [GeoNames](http://www.geonames.org/) databases.

## Installation

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

The configuration options:

| Option     | Default                        | Description                             |
|------------|--------------------------------|-----------------------------------------|
|keepTxt     |true                            |Do NOT delete extracted .txt files       |
|storagePath |storage_path().'/meta/geonames' |Storage path for downloaded files        |
|ignoreTables|array('geonames_alternate_names)|Do NOT import/skip tables in this array()|

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

## Indices a.k.a. Indexes ( !!! READ THIS !!! )
The tables only have basic indices. Especially the 'geonames_geonames' table probably need extra indices for performance. For example for searching cities in the table with 'ascii_name' of the city, the best way to index it is to use a composite index.  For exampe:

 	ALTER TABLE geonames_geonames ADD INDEX (ascii_name,feature_code);
 	
 Then do a search with 'P' as the 'feature_code' (P: city, village,...)
 
 	SELECT * FROM geonames_geonames WHERE ascii_name='Turku' and feature_class='P';
 	
 In my development system (5400RPM HDD, 2GB RAM, 2 Cores running in virtual machine), **WITHOUT** index, this query takes about **2 min 30 sec** to return a single row. Ironically the operation of adding the index with the command above takes about **3 min** to complete.
 
 Once the index is added, **WITH** index it takes **0.02 sec** to return the same row. (query cache was cleared)
 
 Remember that composite indices help only if you build your query with the same element order that the index is created. For example, the index given as example above will speed up **WHERE ascii_name='Turku'** but it **WON'T** speed up **WHERE feature_class='p'**. 
 
 You can find much more information at the MySQL documentation page [Multiple-Column Indexes](http://dev.mysql.com/doc/refman/5.7/en/multiple-column-indexes.html)
