<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Syllabus
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name_ja
 * @property string $name_en
 * @property int $course
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Form[] $forms
 * @property-read int|null $forms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lesson[] $lessons
 * @property-read int|null $lessons_count
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereAbstract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereCompulsory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Syllabus whereCourse($value)
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
 */
class Syllabus extends Model
{
    use HasFactory;

    protected $fillable = [
        'course',
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

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
