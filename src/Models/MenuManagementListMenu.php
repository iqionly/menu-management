<?php

namespace Iqionly\MenuManagement\Models;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\QueriesRelationships;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuManagementListMenu extends Model {
    use SoftDeletes, HasTimestamps, HasRelationships, QueriesRelationships, MassPrunable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Indicate the fillable attributes
     * 
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'priority',
        'name',
        'icons_path',
        'description',
    ];

    /**
     * Indicate the guarded attributes
     * 
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d',
            'updated_at' => 'datetime:Y-m-d',
        ];
    }

    public function getIconsPathAttribute($value) {
        return $value === 1 || $value == true ? true : $value;
    }

    /**
     * Relationship child of menus
     *
     */
    public function menu_management_list_menus()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}