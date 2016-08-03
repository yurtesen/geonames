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
 * Date: 06-Jul-16
 * Time: 11:30 AM
 */

namespace Yurtesen\Geonames\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Yurtesen\Geonames\Models\GeonamesGeoname
 *
 * @property integer $geoname_id
 * @property string $name
 * @property string $ascii_name
 * @property string $alternate_names
 * @property float $latitude
 * @property float $longitude
 * @property string $feature_class
 * @property string $feature_code
 * @property string $country_code
 * @property string $cc2
 * @property string $admin1_code
 * @property string $admin2_code
 * @property string $admin3_code
 * @property string $admin4_code
 * @property integer $population
 * @property integer $elevation
 * @property integer $dem
 * @property string $timezone_id
 * @property string $modified_at
 * @property-read \Yurtesen\Geonames\Models\GeonamesAlternateName $alternateName
 * @property-read \Yurtesen\Geonames\Models\GeonamesTimezone $timeZone
 * @property-read \Yurtesen\Geonames\Models\GeonamesCountryInfo $country
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereGeonameId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereAsciiName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereAlternateNames($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereFeatureClass($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereFeatureCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereCountryCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereCc2($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereAdmin1Code($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereAdmin2Code($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereAdmin3Code($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereAdmin4Code($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname wherePopulation($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereElevation($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereDem($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereTimezoneId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname whereModifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname admin1()
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname city($name, $featureCodes = null, $limit = null)
 * @mixin \Eloquent
 */
class GeonamesGeoname extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'geoname_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * One-to-One relation with GeonamesAlternateNames
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function alternateName()
    {
        return $this->hasOne(GeonamesAlternateName::class, 'geoname_id', 'geoname_id');
    }

    /**
     * One-to-One relation with GeonamesAlternateNames
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function timeZone()
    {
        return $this->hasOne(GeonamesTimezone::class, 'timezone_id', 'timezone_id');
    }

    /**
     * One-to-Onex`x     relation with GeonamesCountryInfos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country()
    {
        return $this->hasOne(GeonamesCountryInfo::class, 'iso', 'country_code');
    }


    /**
     * Return admin1 information in result
     * This is very ugly, any suggestions are welcome
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAdmin1($query)
    {
        /* @var $query Builder */
        $query = $query->leftJoin('geonames_admin1_codes as admin1', function ($join) {
            $join->on(
                DB::raw('admin1.code = CONCAT_WS(\'.\',' .
                    'geonames_geonames.country_code,' .
                    'geonames_geonames.admin1_code)'),
                DB::raw(''),
                DB::raw('')

            );
        })->select(
            'geonames_geonames.*',
            'admin1.code as admin1_code',
            'admin1.name as admin1_name',
            'admin1.name_ascii as admin1_name_ascii',
            'admin1.geoname_id as admin1_geoname_id'
        );
        return $query;
    }

    /**
     * Build a query to find major cities. Accepts wildcards eg. 'Helsin%'
     *
     * Requires Index:
     * ALTER TABLE geonames_geonames ADD index (name,feature_code,feature_class);
     *
     * @param String $name
     * @param array $featureCodes List of feature codes to use when returning results
     *                            defaults to ['PPLC','PPLA','PPLA2']
     * @param Integer $limit
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeCity($query, $name, $featureCodes = null, $limit = null)
    {
        if (!isset($featureCodes)) $featureCodes = ['PPLC', 'PPLA', 'PPLA2'];
        $queryLimit = '';
        if (isset($limit) && is_numeric($limit)) $queryLimit = 'LIMIT ' . $limit;
        return $query->where('geonames_geonames.name', 'LIKE', $name)
            ->where('feature_class', 'P')
            ->whereIn('feature_code', $featureCodes);
    }


}