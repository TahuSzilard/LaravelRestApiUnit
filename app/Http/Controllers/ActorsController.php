<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use App\Http\Requests\ActorsRequest;

/**
 * @OA\Info(
 *     title="Actors API",
 *     version="1.0",
 *     description="API dokumentáció a színészek kezeléséhez"
 * )
 */
class ActorsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/actors",
     *     summary="Listázza az összes színészt",
     *     tags={"Actors"},
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
    public function index()
    {
        $actors = Actor::all();
        return response()->json([
            'actors' => $actors,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/actors",
     *     summary="Új színész létrehozása",
     *     tags={"Actors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ActorInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sikeresen létrehozva",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="actor", ref="#/components/schemas/Actor")
     *         )
     *     )
     * )
     */
    public function store(ActorsRequest $request)
    {
        $actor = Actor::create($request->all());

        return response()->json([
            'actor' => $actor,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/actors/{id}",
     *     summary="Színész módosítása",
     *     tags={"Actors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A színész azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ActorInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres frissítés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="actors", ref="#/components/schemas/Actor")
     *         )
     *     )
     * )
     */
    public function update(ActorsRequest $request, $id)
    {
        $actor = Actor::findOrFail($id);
        $actor->update($request->all());

        return response()->json([
            'actors' => $actor,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/actors/{id}",
     *     summary="Színész törlése",
     *     tags={"Actors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A színész azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres törlés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="actor deleted successfully"),
     *             @OA\Property(property="id", type="integer")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $actor = Actor::findOrFail($id);
        $actor->delete();

        return response()->json([
            'message' => 'Actor deleted successfully'
        ], 200);
    }

    /**
 * @OA\Get(
 *     path="/api/actors/{id}/films",
 *     summary="Lekéri a színész összes filmjét",
 *     tags={"Actors"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="A színész azonosítója",
 *         @OA\Schema(type="integer")
 *     ),
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
    public function getActorFilms($id)
    {
        $actor = Actor::with('films')->findOrFail($id);

        return response()->json([
            'films' => $actor->films,
        ]);
    }
}

/**
 * @OA\Schema(
 *     schema="Actor",
 *     type="object",
 *     title="Színész adatai",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Tom Hanks"),
 *     @OA\Property(property="birth_year", type="integer", example=1956)
 * )
 *
 * @OA\Schema(
 *     schema="ActorInput",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Leonardo DiCaprio"),
 *     @OA\Property(property="birth_year", type="integer", example=1974)
 * )
 */
