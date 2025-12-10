<?php

namespace App\Http\Controllers;

use App\Models\Director;
use Illuminate\Http\Request;
use App\Http\Requests\DirectorsRequest;

/**
 * @OA\Info(
 *     title="Directors API",
 *     version="1.0",
 *     description="API dokumentáció a rendezők kezeléséhez"
 * )
 */
class DirectorsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/directors",
     *     summary="Listázza az összes rendezőt",
     *     tags={"Directors"},
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres lekérés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="directors",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Director")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $directors = Director::all();
        return response()->json([
            'directors' => $directors,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/directors",
     *     summary="Új rendező létrehozása",
     *     tags={"Directors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DirectorInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sikeresen létrehozva",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="director", ref="#/components/schemas/Director")
     *         )
     *     )
     * )
     */
    public function store(DirectorsRequest $request)
    {
        $director = Director::create($request->all());

        return response()->json([
            'director' => $director,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/directors/{id}",
     *     summary="Rendező módosítása",
     *     tags={"Directors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A rendező azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DirectorInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres frissítés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="directors", ref="#/components/schemas/Director")
     *         )
     *     )
     * )
     */
    public function update(DirectorsRequest $request, $id)
    {
        $director = Director::findOrFail($id);
        $director->update($request->all());

        return response()->json([
            'directors' => $director,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/directors/{id}",
     *     summary="Rendező törlése",
     *     tags={"Directors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A rendező azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres törlés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="director deleted successfully"),
     *             @OA\Property(property="id", type="integer")
     *         )
     *     )
     * )
     */
    public function destroy($id) 
    {
        $director = Director::findOrFail($id);
        $director->delete();

        return response()->json([
            'message' => 'Director deleted successfully'
        ], 200);
    }

    public function getDirectorFilms($id)
    {
        $director = Director::with('films')->findOrFail($id);

        return response()->json([
            'films' => $director->films,
        ]);
    }
}

/**
 * @OA\Schema(
 *     schema="Director",
 *     type="object",
 *     title="Rendező adatai",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Steven Spielberg"),
 *     @OA\Property(property="birth_year", type="integer", example=1946)
 * )
 *
 * @OA\Schema(
 *     schema="DirectorInput",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Christopher Nolan"),
 *     @OA\Property(property="birth_year", type="integer", example=1970)
 * )
 */

/**
 * @OA\Get(
 *     path="/api/directors/{id}/films",
 *     summary="Lekéri a rendező összes filmjét",
 *     tags={"Directors"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="A rendező azonosítója",
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

