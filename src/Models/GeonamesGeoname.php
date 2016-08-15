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
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname admin1()
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname countryInfo()
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesGeoname city($name, $featureCodes = null, $limit = null)
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
     *  Most useful fields which we want in every result which utilize our scopes
     *
     * @var array
     */
    private $usefulScopeColumns = [
        'geonames_geonames.geoname_id',
        'geonames_geonames.name',
        'geonames_geonames.country_code'
    ];

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
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAdmin1($query)
    {
        $table = 'geonames_geonames';

        if (!isset($query->getQuery()->columns))
            $query = $query->addSelect($this->usefulScopeColumns);

        $query = $query
            ->leftJoin('geonames_admin1_codes as admin1', 'admin1.code', '=',
                DB::raw('CONCAT_WS(\'.\',' .
                    $table . '.country_code,' .
                    $table . '.admin1_code)')
            )
            ->addSelect(
                'admin1.geoname_id as admin1_geoname_id',
                'admin1.name as admin1_name'
            );

        return $query;
    }

    /**
     * Return country information in result
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeCountryInfo($query)
    {
        $table = 'geonames_geonames';

        if (!isset($query->getQuery()->columns))
            $query = $query->addSelect($this->usefulScopeColumns);

        $query = $query
            ->leftJoin('geonames_country_infos as country_info', $table . '.country_code', '=',
                'country_info.iso'
            )
            ->addSelect(
                'country_info.geoname_id as country_info_geoname_id',
                'country_info.country as country_info_country'
            );

        return $query;
    }


    /**
     * Build a query to find major cities. Accepts wildcards eg. 'Helsin%'
     *
     * Suggested index for search:
     * ALTER TABLE geonames_geonames ADD INDEX geonames_geonames_feature_name_index
     *                                                          (`feature_class`,`feature_code`,`name`);
     * and if you will limit queries by country you should also use:
     * ALTER TABLE geonames_geonames ADD INDEX geonames_geonames_country_feature_name_index
     *                                                          (`country_code`,`feature_class`,`feature_code`,`name`);
     *
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @param String $name
     * @param array $featureCodes List of feature codes to use when returning results
     *                            defaults to ['PPLC','PPLA','PPLA2']
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeCity($query, $name = null, $featureCodes = ['PPLC', 'PPLA', 'PPLA2', 'PPLA3'])
    {
        $table = 'geonames_geonames';

        if (!isset($query->getQuery()->columns))
            $query = $query->addSelect($this->usefulScopeColumns);

        if ($name !== null)
            $query = $query->where($table . '.name', 'LIKE', $name);

        $query = $query
            ->where($table . '.feature_class', 'P')
            ->whereIn($table . '.feature_code', $featureCodes);

        return $query;
    }


}