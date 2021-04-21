<?php

namespace App\Models;

use App\Enums\ModelType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * App\Models\Model
 *
 * @property int $id
 * @property int $syllabus_id
 * @property int $type
 * @property bool $is_basic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Model newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereIsBasic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Model extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'is_basic' => 'boolean'
    ];

    public function getType(): ModelType
    {
        return new ModelType($this->type);
    }
}
