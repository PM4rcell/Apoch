<?php

namespace App\Rules;

use App\Models\Movie;
use App\Models\Screening;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class NoOverlappingScreening implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $data = [];
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {        
        $auditoriumId = $this->data['auditorium_id'];
        $movieId = $this->data['movie_id'];
        $startTime = Carbon::parse($this->data['start_time']);

        $movie = Movie::findOrFail($movieId);
        $endTime = $startTime->copy()->addMinutes($movie->runtime_min + 20);

        $overlap = Screening::where('auditorium_id', $auditoriumId)
            ->where('id', '!=', $this->data['id'] ?? 0)
            ->where('start_time', '<', $endTime)
            ->whereRaw('DATE_ADD(start_time, INTERVAL (SELECT runtime_min FROM movies WHERE id = screenings.movie_id) + 20 MINUTE) > ?', [$startTime])
            ->exists();
        
        if($overlap){
            $fail('Auditorium is booked for overlapping time.');
        }

    }

    public function setData(array $data): static{
        $this->data = $data;
        return $this;
    }
}
