<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Http\Requests\StoreScreeningRequest;
use App\Http\Requests\UpdateScreeningRequest;
use App\Http\Resources\ScreeningResource;
use App\Http\Resources\SeatResource;
use App\Models\Language;
use Illuminate\Support\Facades\Lang;

class ScreeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        

        $screenings = Screening::query()
        ->with([
            'auditorium', 
            'movie' => fn($query) => $query->with(['poster', 'era']), 
            'language'
        ]);   
        
        if (request()->filled('era_id')) {
            $eraId = request()->input('era_id');
            $screenings->whereHas('movie', function ($query) use ($eraId) {
                $query->where('era_id', $eraId);
            });
        }
        if (request()->filled('movie_id')) {
            $screenings->where('movie_id', request()->input('movie_id'));
        }
        if (request()->filled('date')) {
            $screenings->where('start_time', request()->input('date'));
        }
        return ScreeningResource::collection($screenings->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScreeningRequest $request)
    {
        $data = $request->validated();
        $language = Language::firstOrCreate(['name' => $data['language']]);
        $data['language_id'] = $language->id;

        $screening = Screening::create($data);

        $screening->load([
            'auditorium', 
            'movie' => fn($query) => $query->with(['poster', 'era']), 
            'language'
        ]);
        return new ScreeningResource($screening);   
    }

    /**
     * Display the specified resource.
     */
    public function show(Screening $screening)
    {
        $screening->load([
            'auditorium', 
            'movie' => fn($query) => $query->with(['poster', 'era']), 
            'language'
        ]);
        return new ScreeningResource($screening);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScreeningRequest $request, Screening $screening)
    {
        $data = $request->validated();
        
        $language = Language::firstOrCreate(['name' => $data['language']]);
        $data['language_id'] = $language->id;
        unset($data['language']);

        $screening->update($data);        
        
        $screening->load([
            'auditorium', 
            'movie' => fn($query) => $query->with(['poster', 'era']), 
            'language'
        ]);
        return new ScreeningResource($screening);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Screening $screening)
    {
        $screening->delete();
        return response()->noContent();
    }

    /**
     * Get seats for a specific screening.
     */
    public function getScreeningSeats($id){
        $screening = Screening::with('auditorium.seats')->findOrFail($id);
        $seats = $screening->auditorium->seats;

        return response()->json([
            'screening_id' => $screening->id,
            'auditorium_id' => $screening->auditorium->id,
            'seats' => SeatResource::collection($seats),
        ]);
    }
}
