<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use App\Http\Requests\FilmsRequest;
use App\Models\Actor;

/**
 * @OA\Info(
 *     title="Films API",
 *     version="1.0",
 *     description="API dokumentáció a filmek kezeléséhez"
 * )
 */
class FilmsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/films",
     *     summary="Listázza az összes filmet",
     *     tags={"Films"},
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres lekérés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="films",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Film")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $films = Film::all();
        return response()->json([
            'films' => $films,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/films",
     *     summary="Új film létrehozása",
     *     tags={"Films"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FilmInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sikeresen létrehozva",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="film", ref="#/components/schemas/Film")
     *         )
     *     )
     * )
     */
    public function store(FilmsRequest $request)
    {
        $film = Film::create($request->all());

        return response()->json([
            'film' => $film,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/films/{id}",
     *     summary="Film módosítása",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A film azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FilmInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres frissítés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="film", ref="#/components/schemas/Film")
     *         )
     *     )
     * )
     */
    public function update(FilmsRequest $request, $id)
    {
        $film = Film::findOrFail($id);
        $film->update($request->all());

        return response()->json([
            'film' => $film,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/films/{id}",
     *     summary="Film törlése",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A film azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres törlés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Film deleted successfully"),
     *             @OA\Property(property="id", type="integer")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $film = Film::findOrFail($id);
        $film->delete();
        return response()->json([
            'message' => 'Film deleted successfully',
            'id' => $id
        ]);
    }

    /**
 * @OA\Get(
 *     path="/api/films/{id}/actors",
 *     summary="Lekéri az adott film összes színészét",
 *     tags={"Films"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="A film azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sikeres lekérés",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="actors",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Actor")
 *             )
 *         )
 *     )
 * )
 */
    public function getFilmActors($id)
    {
        $film = Film::with('actors')->findOrFail($id);

        return response()->json([
            'actors' => $film->actors,
        ]);
    }


    /**
 * @OA\Post(
 *     path="/api/films/{id}/actors",
 *     summary="Színészek hozzáadása egy filmhez",
 *     tags={"Films"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="A film azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="actor_ids",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 example={1,2,3}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Színészek sikeresen hozzáadva a filmhez",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="actors", type="array", @OA\Items(ref="#/components/schemas/Actor"))
 *         )
 *     )
 * )
 */
    public function addFilmActors(Request $request, $id)
    {/* 
        $request->validate([
            'actor_ids' => 'required|array',
            'actor_ids.*' => 'integer|exists:actors,id',
            'is_lead' => 'sometimes|boolean'
        ]);
*/
        $film = Film::findOrFail($id);

        $attachData = [];
        foreach ($request->actor_ids as $actor_id) {
            $attachData[$actor_id] = [
                'is_lead' => $request->input('is_lead', 0)
            ];
        }

        $film->actors()->syncWithoutDetaching($attachData);
        $film->load('actors');

        return response()->json([
            'actors' => $film->actors
        ], 200);
    }

    public function updateFilmActor(Request $request, $filmId, $actorId)
{
    $request->validate([
        'is_lead' => 'required|boolean',
    ]);

    $film = Film::findOrFail($filmId);
    $actor = Actor::findOrFail($actorId);

    $film->actors()->syncWithoutDetaching([
        $actor->id => ['is_lead' => $request->input('is_lead')]
    ]);

    return response()->json([
        'message' => 'Film-actor pivot updated successfully',
        'actor' => $actor,
        'pivot' => [
            'is_lead' => $film->actors()->where('actor_id', $actor->id)->first()->pivot->is_lead,
        ]
    ]);
}

public function removeFilmActor(Film $film, Actor $actor)
{
    
    if (!$film->actors()->where('actor_id', $actor->id)->exists()) {
        return response()->json([
            'message' => 'Actor not attached to this film'
        ], 404);
    }

    
    $film->actors()->detach($actor->id);

    return response()->json([
        'message' => 'Actor removed from film successfully',
        'film_id' => $film->id,
        'actor_id' => $actor->id
    ], 200);
}



/**
 * @OA\Get(
 *     path="/api/films/{id}/directors",
 *     summary="Lekéri az adott film rendezőjét",
 *     tags={"Films"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="A film azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sikeres lekérés",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="director",
 *                 ref="#/components/schemas/Director"
 *             )
 *         )
 *     )
 * )
 */
public function getFilmDirector($id)
{
    $film = Film::with('director')->findOrFail($id);

    if (!$film->director) {
        return response()->json([
            'message' => 'This film has no director assigned.'
        ], 404);
    }

    return response()->json([
        'director' => $film->director
    ]);
}


/**
 * @OA\Post(
 *     path="/api/films/{id}/directors",
 *     summary="Rendező hozzárendelése egy filmhez",
 *     tags={"Films"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="A film azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="director_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Rendező sikeresen hozzárendelve",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Director assigned successfully"),
 *             @OA\Property(property="film", ref="#/components/schemas/Film")
 *         )
 *     )
 * )
 */
public function addFilmDirector(Request $request, $id)
{
    $request->validate([
        'director_id' => 'required|integer|exists:directors,id'
    ]);

    $film = Film::findOrFail($id);
    $film->director_id = $request->director_id;
    $film->save();

    $film->load('director');

    return response()->json([
        'message' => 'Director assigned successfully',
        'film' => $film
    ]);
}

/**
 * @OA\Patch(
 *     path="/api/films/{film}/directors/{director}",
 *     summary="A film rendezőjének módosítása",
 *     tags={"Films"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="film",
 *         in="path",
 *         required=true,
 *         description="A film azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="director",
 *         in="path",
 *         required=true,
 *         description="A rendező azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Rendező sikeresen módosítva",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Director updated successfully"),
 *             @OA\Property(property="film", ref="#/components/schemas/Film")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Film vagy rendező nem található"
 *     )
 * )
 */
public function updateFilmDirector($filmId, $directorId)
{
    $film = Film::find($filmId);
    $director = \App\Models\Director::find($directorId);

    if (!$film || !$director) {
        return response()->json(['message' => 'Film or Director not found'], 404);
    }

    $film->director_id = $director->id;
    $film->save();

    return response()->json([
        'message' => 'Director updated successfully',
        'film' => $film
    ], 200);
}


/**
 * @OA\Delete(
 *     path="/api/films/{film}/directors/{director}",
 *     summary="Eltávolítja a film rendezőjét",
 *     tags={"Films"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="film",
 *         in="path",
 *         required=true,
 *         description="A film azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="director",
 *         in="path",
 *         required=true,
 *         description="A rendező azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Rendező sikeresen eltávolítva a filmből",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Director removed from film successfully"),
 *             @OA\Property(property="film_id", type="integer"),
 *             @OA\Property(property="director_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Film vagy rendező nem található, vagy nem kapcsolódik egymáshoz"
 *     )
 * )
 */
public function removeFilmDirector($filmId, $directorId)
{
    $film = Film::find($filmId);

    if (!$film) {
        return response()->json(['message' => 'Film not found'], 404);
    }

    if ($film->director_id != $directorId) {
        return response()->json(['message' => 'This director is not assigned to this film'], 404);
    }

    
    $defaultDirectorId = null; // pl. Unknown
    $film->director_id = $defaultDirectorId;
    $film->save();

    return response()->json([
        'message' => 'Director removed from film successfully, set to default',
        'film_id' => $filmId,
        'previous_director_id' => $directorId,
        'current_director_id' => $defaultDirectorId
    ], 200);
}


}

/**
 * @OA\Schema(
 *     schema="Film",
 *     type="object",
 *     title="Film adatai",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Inception"),
 *     @OA\Property(property="release_year", type="integer", example=2010),
 *     @OA\Property(property="director_id", type="integer", example=2)
 * )
 *
 * @OA\Schema(
 *     schema="FilmInput",
 *     type="object",
 *     required={"title", "director_id"},
 *     @OA\Property(property="title", type="string", example="Interstellar"),
 *     @OA\Property(property="release_year", type="integer", example=2014),
 *     @OA\Property(property="director_id", type="integer", example=1)
 * )
 */
