<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CompulsoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Syllabus
 *
 * @property int $id
 * @property int $course_id
 * @property string $name_ja
 * @property string $name_en
 * @property int $compulsory
 * @property int $credit
 * @property int $quarter
 * @property string $group
 * @property string $teacher
 * @property string $abstract
 * @property string $purpose
 * @property string $precondition
 * @property string $higher_goal
 * @property string $lower_goal
 * @property string $outside_learning
 * @property string $inside_learning
 * @property string $evaluation
 * @property string $text
 * @property string $reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Form[] $forms
 * @property-read int|null $forms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lesson[] $lessons
 * @property-read int|null $lessons_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SyllabusModel[] $modelPivot
 * @property-read int|null $model_pivot_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Model[] $models
 * @property-read int|null $models_count
 * @property-read \App\Models\Score|null $score
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereAbstract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereCompulsory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereHigherGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereInsideLearning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereLowerGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereNameJa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereOutsideLearning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus wherePrecondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereTeacher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Syllabus extends Model
{
    use HasFactory;

    protected $fillable = [
        'compulsory',
        'credit',
        'quarter',
        'group',
        'name_ja',
        'name_en',
        'teacher',
        'abstract',
        'purpose',
        'precondition',
        'higher_goal',
        'lower_goal',
        'outside_learning',
        'inside_learning',
        'evaluation',
        'text',
        'reference',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function models(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Model::class, 'syllabus_model');
    }

    public function modelPivot(): HasMany
    {
        return $this->hasMany(SyllabusModel::class);
    }

    public function score(): HasOne
    {
        return $this->hasOne(Score::class);
    }

    public function getCompulsory(): CompulsoryType
    {
        return new CompulsoryType($this->compulsory);
    }

    public function getCompulsoryLabel(): string
    {
        return CompulsoryType::label($this->getCompulsory());
    }

    public function hasScore(): bool {
        return $this->score !== null;
    }
}
