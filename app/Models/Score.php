<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Score
 *
 * @property int $id
 * @property int $syllabus_id
 * @property int $participants
 * @property int $score_5
 * @property int $score_4
 * @property int $score_3
 * @property int $score_2
 * @property int $score_1
 * @property int $score_0
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Score newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score query()
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereScore0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereScore1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereScore2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereScore3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereScore4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereScore5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'participants',
        'score_5',
        'score_4',
        'score_3',
        'score_2',
        'score_1',
        'score_0',
    ];
}
