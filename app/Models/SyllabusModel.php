<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\SyllabusModel
 *
 * @property int $id
 * @property int $syllabus_id
 * @property int $model_id
 * @property bool $is_basic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereIsBasic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SyllabusModel extends Pivot
{
    use HasFactory;

    protected $casts = [
        'is_basic' => 'boolean'
    ];
}
