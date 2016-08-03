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

use Illuminate\Database\Eloquent\Model;

/**
 * Yurtesen\Geonames\Models\GeonamesAlternateName
 *
 * @property integer $alternate_name_id
 * @property integer $geoname_id
 * @property string $iso_language
 * @property string $alternate_name
 * @property boolean $isPreferredName
 * @property boolean $isShortName
 * @property boolean $isColloquial
 * @property boolean $isHistoric
 * @property-read \Yurtesen\Geonames\Models\GeonamesGeoname $geoname
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereAlternateNameId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereGeonameId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereIsoLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereAlternateName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereIsPreferredName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereIsShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereIsColloquial($value)
 * @method static \Illuminate\Database\Query\Builder|\Yurtesen\Geonames\Models\GeonamesAlternateName whereIsHistoric($value)
 * @mixin \Eloquent
 */
class GeonamesAlternateName extends Model {
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
     * One-to-One relation with GeonamesGeoname
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function geoname() {
        return $this->belongsTo(GeonamesGeoname::class,'geoname_id','geoname_id');
    }

}