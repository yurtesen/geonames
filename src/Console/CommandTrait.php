<?php
/**
 *     This is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * User: Evren Yurtesen
 * Date: 09-Jul-16
 * Time: 1:16 AM
 */

namespace Yurtesen\Geonames\Console;


use Closure;
use DB;
use Illuminate\Console\OutputStyle;
use RuntimeException;
use ZipArchive;

trait CommandTrait
{

    /**
     * Buffer size to use when executing SQL statements in number of rows
     * I experimented and 1000 is a very reasonable value. Larvger values
     * (5000+) may cause too many placeholders error in eloquent.
     *
     * @var int
     */
    protected $bufferSize = 1000;

    /**
     * This is used to map continent keys with geoname_id values
     *
     * @var array
     */
    protected $continentCodes = array(
        'AF' => 6255146,
        'AS' => 6255147,
        'EU' => 6255148,
        'NA' => 6255149,
        'OC' => 6255151,
        'SA' => 6255150,
        'AN' => 6255152,
    );

    /**
     * This is the main list of url,filename and table values we will work with
     *
     * @var array
     */
    protected $files = array(
        'timeZones' => array(
            'url' => 'http://download.geonames.org/export/dump/timeZones.txt',
            'filename' => 'timeZones',
            'table' => 'geonames_timezones',
        ),
        'allCountries' => array(
            'url' => 'http://download.geonames.org/export/dump/allCountries.zip',
            'filename' => 'allCountries',
            'table' => 'geonames_geonames'
        ),
        'countryInfo' => array(
            'url' => 'http://download.geonames.org/export/dump/countryInfo.txt',
            'filename' => 'countryInfo',
            'table' => 'geonames_country_infos'
        ),
        'iso-languagecodes' => array(
            'url' => 'http://download.geonames.org/export/dump/iso-languagecodes.txt',
            'filename' => 'iso-languagecodes',
            'table' => 'geonames_iso_language_codes',
        ),
        'alternateNames' => array(
            'url' => 'http://download.geonames.org/export/dump/alternateNames.zip',
            'filename' => 'alternateNames',
            'table' => 'geonames_alternate_names',
        ),
        'hierarchy' => array(
            'url' => 'http://download.geonames.org/export/dump/hierarchy.zip',
            'filename' => 'hierarchy',
            'table' => 'geonames_hierarchies',
        ),
        'admin1CodesASCII' => array(
            'url' => 'http://download.geonames.org/export/dump/admin1CodesASCII.txt',
            'filename' => 'admin1CodesASCII',
            'table' => 'geonames_admin1_codes',
        ),
        'admin2Codes' => array(
            'url' => 'http://download.geonames.org/export/dump/admin2Codes.txt',
            'filename' => 'admin2Codes',
            'table' => 'geonames_admin2_codes',
        ),
        // Todo: figure out how to manage different languages
        'featureCodes' => array(
            'url' => 'http://download.geonames.org/export/dump/featureCodes_en.txt',
            'filename' => 'featureCodes_en',
            'table' => 'geonames_feature_codes',
        )
    );


    /**
     * Parses the array created from different geonames file lines
     * and converts into key=>value type array
     *
     * @param string $name The config name of the file
     * @param boolean $refresh Set true for truncating table before inserting rows
     *
     */
    protected function parseGeonamesText($name, $refresh = false)
    {
        $fieldsArray = array(
            'allCountries' => function ($row) {
                return array(
                    'geoname_id' => $row[0],
                    'name' => $row[1],
                    'ascii_name' => $row[2] ? $row[2] : null,
                    'alternate_names' => $row[3] ? $row[3] : null,
                    'latitude' => $row[4],
                    'longitude' => $row[5],
                    'feature_class' => $row[6] ? $row[6] : null,
                    'feature_code' => $row[7] ? $row[7] : null,
                    'country_code' => $row[8],
                    'cc2' => $row[9] ? $row[9] : null,
                    'admin1_code' => $row[10] ? $row[10] : null,
                    'admin2_code' => $row[11] ? $row[11] : null,
                    'admin3_code' => $row[12] ? $row[12] : null,
                    'admin4_code' => $row[13] ? $row[13] : null,
                    'population' => $row[14] ? $row[14] : null,
                    'elevation' => $row[15] ? $row[15] : null,
                    'dem' => $row[16] ? $row[16] : null,
                    'timezone_id' => $row[17] ? $row[17] : null,
                    'modified_at' => trim($row[18]),
                );
            },
            'iso-languagecodes' => function ($row) {
                return array(
                    'iso_639_3' => $row[0],
                    'iso_639_2' => $row[1] ? $row[1] : null,
                    'iso_639_1' => $row[2] ? $row[2] : null,
                    'language_name' => trim($row[3]),
                );
            },
            'hierarchy' => function ($row) {
                return array(
                    'parent_id' => $row[0],
                    'child_id' => $row[1],
                    'type' => trim($row[2]),
                );
            },
            'alternateNames' => function ($row) {
                return array(
                    'alternate_name_id' => $row[0],
                    'geoname_id' => $row[1],
                    'iso_language' => $row[2] ? $row[2] : null,
                    'alternate_name' => $row[3] ? $row[3] : null,
                    'isPreferredName' => $row[4] ? 1 : 0,
                    'isShortName' => $row[5] ? 1 : 0,
                    'isColloquial' => $row[6] ? 1 : 0,
                    'isHistoric' => $row[7] ? 1 : 0,
                );
            },
            'timeZones' => function ($row) {
                // Skip unusual comment line in this file
                if ($row[0] === 'CountryCode') return null;
                return array(
                    'country_code' => $row[0],
                    'timezone_id' => $row[1],
                    'gmt_offset' => $row[2],
                    'dst_offset' => $row[3],
                    'raw_offset' => $row[4]
                );
            },
            'countryInfo' => function ($row) {
                return array(
                    'iso' => $row[0],
                    'iso3' => $row[1],
                    'iso_numeric' => $row[2],
                    'fips' => $row[3] ? $row[3] : null,
                    'country' => $row[4] ? $row[4] : null,
                    'capital' => $row[5] ? $row[5] : null,
                    'area' => $row[6] ? $row[6] : null,
                    'population' => $row[7] ? $row[7] : null,
                    'continent_code' => $row[8],
                    'continent_id' => $this->continentCodes[$row[8]],
                    'tld' => $row[9] ? $row[9] : null,
                    'currency_code' => $row[10] ? $row[10] : null,
                    'currency_name' => $row[11] ? $row[11] : null,
                    'phone' => $row[12] ? $row[12] : null,
                    'postal_code_format' => $row[13] ? $row[13] : null,
                    'postal_code_regex' => $row[14] ? $row[14] : null,
                    'languages' => $row[15] ? $row[15] : null,
                    'geoname_id' => $row[16] ? $row[16] : null,
                    'neighbors' => $row[17] ? $row[17] : null,
                    'equivalent_fips_code' => $row[18] ? trim($row[18]) : null,
                );
            },
            'featureCodes' => function ($row) {
                return array(
                    'code' => $row[0],
                    'name' => $row[1],
                    'description' => trim($row[2]),
                );
            },
            'admin1CodesASCII' => function ($row) {
                return array(
                    'code' => $row[0],
                    'name' => $row[1],
                    'name_ascii' => $row[2],
                    'geoname_id' => $row[3],
                );
            },
            'admin2Codes' => function ($row) {
                return array(
                    'code' => $row[0],
                    'name' => $row[1],
                    'name_ascii' => $row[2],
                    'geoname_id' => $row[3],
                );
            },
        );

        // This will greatly improve the performance of inserts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tableName = $this->files[$name]['table'];
        if (DB::table($tableName)->count() === 0 || $refresh) {
            $this->line('<info>Database:</info> Truncating table '.$tableName);
            DB::table($tableName)->truncate();
        }
        $buffer = array();
        $fields = $fieldsArray[$name];
        $this->parseFile($name, function ($row) use (&$buffer, $fields, $tableName) {
            $insert = $fields($row);
            if (isset($insert) && is_array($insert)) {
                $buffer[] = $insert;
            }
            if (count($buffer) === $this->bufferSize) {
                $this->updateOrInsertMultiple($tableName, $buffer);
                $buffer = array();
            }
        });

        // Insert leftovers in buffer
        if (count($buffer) > 0)
            $this->updateOrInsertMultiple($tableName, $buffer);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Parse a given file and insert into databaase using closure.
     *
     * @param  string $name
     * @param  Closure $callback
     * @return void
     */
    protected function parseFile($name, Closure $callback)
    {
        $url = $this->files[$name]['url'];
        $basename = basename($url);
        $storagePath = config('geonames.storagePath');
        // Final file path
        $path = $storagePath . '/' . $this->files[$name]['filename'] . '.txt';

        // Check if file exists (should be since we just downloaded them)
        $downloadedFilePath = $storagePath . '/' . $basename;
        if (!file_exists($downloadedFilePath)) {
            throw new RuntimeException('File does not exist: ' . $downloadedFilePath);
        }
        // If it is a zip file we must unzip it
        if (substr($basename, -4) === '.zip') {
            $this->unZip($name);
        }

        $steps = $this->getLineCount($path);

        /* @var $output OutputStyle */
        $output = $this->getOutput();
        $bar = $output->createProgressBar($steps);
        $bar->setFormat('<info>Seeding File:</info> ' . basename($path) . ' %current%/%max% %bar% %percent%% <info>Remaining Time:</info> %remaining%');

        $steps = 0;
        $fh = fopen($path, 'r');
        if (!$fh) {
            throw new RuntimeException("Can not open file: $path");
        }
        while (!feof($fh)) {
            $line = fgets($fh);
            // ignore empty lines and comments
            if (!$line or $line === '' or strpos($line, '#') === 0) continue;
            // Geonames format is tab seperated
            $line = explode("\t", $line);
            // Insert using closure
            $callback($line);
            $steps++;
            if (isset($bar) && $steps % ($this->bufferSize * 10) === 0)
                $bar->advance($this->bufferSize * 10);
        }
        fclose($fh);
        if (isset($bar)) {
            $bar->finish();
            $output->newLine();
        }
        // If we wont keep txt version delete file
        if (substr($basename, -4) === '.zip' && !config('geonames.keepTxt')) {
            $this->line('<info>Removing File:</info> ' . basename($path));
            unlink($path);
        }
    }


    /**
     * Read the file and get line count
     * Not very efficient but does the job well...
     *
     * @param  string $path
     * @return int $count
     */
    protected function getLineCount($path)
    {
        $fh = fopen($path, 'r');
        if (!$fh) {
            throw new RuntimeException("Can not open file: $path");
        }
        $fileSize = @filesize($path);
        /* @var $output OutputStyle */
        $output = $this->getOutput();
        $bar = $output->createProgressBar($fileSize);
        $bar->setFormat('<info>Reading File:</info> ' . basename($path) . ' %bar% %percent%% <info>Remaining Time:</info> %remaining%');

        $steps = 0;
        $currentSize = 0;
        while (!feof($fh)) {
            $line = fgets($fh);
            $currentSize += strlen($line);
            // ignore empty lines and comments
            if (!$line or $line === '' or strpos($line, '#') === 0) continue;
            $steps++;
            // Reading is so much faster, must slow down advances
            if (isset($bar) && $steps % ($this->bufferSize * 100) === 0) {
                $bar->advance($currentSize);
                $currentSize = 0;
            }
        }
        fclose($fh);
        if (isset($bar)) {
            $bar->finish();
            $output->newLine();
        }
        return $steps;
    }

    /**
     * Create and run a single SQL INSERT query for multiple rows of data.
     * $data argument is an array of database row arrays in key=>value pairs.
     * This speeds up inserts ~50 times compared to line-by-line inserts.
     *
     * Note: The $data must be an array of arrays and have at least 2 elements.
     *
     * @param  string $tableName
     * @param  array (array()) $data
     * @return boolean
     */
    protected function updateOrInsertMultiple($tableName, $data)
    {
        $fields = '`' . implode('`,`', array_keys($data[0])) . '`';
        // Create strings for variables
        $questionMarks = '';
        $updateList = '';
        foreach (array_keys($data[0]) as $key) {
            $updateList .= '`' . $key . '`' . '=VALUES(' . '`' . $key . '`' . '),';
            $questionMarks .= '?,';
        }
        // Remove last extra comma
        $updateList = rtrim($updateList, ',');
        $questionMarks = rtrim($questionMarks, ',');

        // If files were not in sync, there may be missing entries in some tables
        $sql = /** @lang text */
            'INSERT IGNORE INTO ' . $tableName . ' (' . $fields . ') ';
        $sql .= 'VALUES ' . PHP_EOL;
        // Set placeholders
        $sql .= '(' . $questionMarks . ')';
        for ($i = 1; $i < count($data); $i++) {
            $sql .= ',' . PHP_EOL . '(' . $questionMarks . ')';
        }
        $sql .= PHP_EOL;
        $valueArray = array();
        foreach ($data as $rows) {
            foreach ($rows as $key => $value) {
                $valueArray[] = $value;
            }
        }
        // Set action on duplicate key updates
        $sql .= "ON DUPLICATE KEY UPDATE ";
        $sql .= $updateList;

        return DB::insert($sql, $valueArray);
    }


    /**
     * Download a file if it does not exist
     *
     * @param Boolean $update Update files
     *
     */
    protected function downloadAllFiles($update = false)
    {
        $files = array_keys($this->getFilesArray());
        foreach ($files as $name) {
            $this->downloadFile($name, $update);
        }
    }

    /**
     * Download a file if it does not exist
     *
     * @param String $url Download URL
     * @param String $path Storage path
     * @param Boolean $force Force re-download of files
     *
     * @return boolean
     */
    protected function downloadFile($name, $update = false)
    {
        $url = $this->files[$name]['url'];
        $storagePath = config('geonames.storagePath');
        $txtFileName = $this->files[$name]['filename'] . '.txt';
        $urlSize = $this->getUrlSize($url);
        $urlFileName = basename($url);

        $fileSize = @filesize($storagePath . '/' . $urlFileName);
        if ($fileSize) {
            if ($fileSize === $urlSize) {
                $this->line('<info>File Exists:</info> ' . $urlFileName . ' with same size as remote geonames file exists.');
                return true;
            } else if ($fileSize !== $urlSize && !$update) {
                $this->line('<info>File Exists:</info> ' . $urlFileName . ' with different size as remote geonames file exists. You should consider downloading newest files.');
                return true;
            } else {
                // If we are here, we will re-download zip file, so it is worthwhile to remoeve the old txt version first
                $extractedFilePath = $storagePath . '/' . $txtFileName;
                if (substr($urlFileName, -4) === '.zip' && file_exists($extractedFilePath)) {
                    $this->line('<info>Removing Old File:</info> ' . basename($extractedFilePath));
                    unlink($extractedFilePath);
                }
            }
        }

        // Create download directory
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        // Open file and truncate it for writing (requires fopen_wrappers)
        $targetFile = $storagePath . '/' . $urlFileName;
        $targetFP = fopen($targetFile, 'w+');
        if ($targetFP === false) {
            throw new RuntimeException('Can not open file for writing: ' . $targetFile);
        }

        $sourceFP = fopen($url, 'r');
        if ($sourceFP === false) {
            throw new RuntimeException('Can not open file for reading: ' . $url);
        }

        $bufferSize = 1024 * 1024;
        // Size should be at leaast 1 to show remaining time...
        $steps = $urlSize / $bufferSize + 1;
        /* @var $output OutputStyle */
        $output = $this->getOutput();
        $bar = $output->createProgressBar($steps);
        $bar->setFormat('<info>Downloading:</info> ' . $urlFileName . ' %bar% %percent%% <info>Remaining Time:</info> %remaining%');
        while (!feof($sourceFP)) {
            fwrite($targetFP, stream_get_contents($sourceFP, $bufferSize));
            $bar->advance();
        }
        $bar->finish();
        $output->newLine();

        clearstatcache(true, $targetFile);
        $this->line('<info>File Downloaded:</info> ' . $urlFileName . ' - ' . filesize($targetFile) . ' bytes.');

        return true;
    }

    /**
     * Unzip the file
     *
     * @param  string $name
     */
    protected function unZip($name)
    {
        $zipFileName = basename($this->files[$name]['url']);
        if (!substr($zipFileName, -4) === '.zip')
            throw new RuntimeException($zipFileName . ' does not have .zip extension');

        // Final file path
        $storagePath = config('geonames.storagePath');
        $extractedFile = $this->files[$name]['filename'] . '.txt';
        $path = $storagePath . '/' . $extractedFile;

        // Open zip archive because we need the size of extracted file
        $zipArchive = new ZipArchive;
        $zipArchive->open($storagePath . '/' . $zipFileName);

        if (file_exists($path)) {
            $uncompressedSize = $zipArchive->statName($extractedFile)['size'];
            $fileSize = filesize($path);
            if ($uncompressedSize !== $fileSize) {
                $this->line('<info>Existing File:</info> ' . basename($path) . ' size does not match the one in ' . $zipFileName);
            } else {
                // Do not extract again
                $this->line('<info>Existing File:</info> ' . 'Found ' . basename($path) . ' file extracted from ' . $zipFileName);
                $zipArchive->close();
                return;
            }
        }
        // File does not exist or size does not match
        $this->line('<info>Extracting File:</info> ' . $extractedFile . ' from ' . $zipFileName . ' ...!!!Please Wait!!!...');
        // Extract file
        $zipArchive->extractTo($storagePath . '/', $extractedFile);
        $zipArchive->close();
    }

    /**
     * Get Remote File Size
     *
     * @param string $url remote address
     * @return int|boolean URL size in bytes or false
     */
    protected function getUrlSize($url)
    {
        $data = get_headers($url, true);
        if (isset($data['Content-Length']))
            return (int)$data['Content-Length'];
        return false;
    }

    /**
     * Returns files array after removing entries in
     * ignoreTables config option
     *
     * @return array
     */
    protected function getFilesArray()
    {
        $data = $this->files;
        foreach ($data as $key => $value) {
            if (in_array($value['table'], config('geonames.ignoreTables'))) {
                unset($data[$key]);
            }
        }
        return $data;
    }


}