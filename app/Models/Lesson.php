<?php

namespace App\Models;

use App\Enums\LessonSatelliteType;
use App\Enums\LessonType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Lesson
 *
 * @property int $id
 * @property int $syllabus_id
 * @property int $number
 * @property string $content
 * @property int $satellite
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereSatellite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'content',
        'satellite',
        'type'
    ];

    public function getSatelliteType(): LessonSatelliteType
    {
        return new LessonSatelliteType($this->satellite);
    }

    public function hasSatellite(): bool
    {
        return $this->getSatelliteType()->equals(LessonSatelliteType::EXIST());
    }

    public function getLessonType(): LessonType
    {
        return new LessonType($this->type);
    }

    public function isInPersonal(): bool
    {
        $type = $this->getLessonType();

        return $type->equals(LessonType::IN_PERSON()) || $type->equals(LessonType::BOTH());
    }

    public function isVideo(): bool
    {
        $type = $this->getLessonType();

        return $type->equals(LessonType::VIDEO()) || $type->equals(LessonType::BOTH());
    }
}
