<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use App\Http\Requests\SeriesRequest;

/**
 * @OA\Info(
 *     title="Series API",
 *     version="1.0",
 *     description="API dokumentáció a sorozatok kezeléséhez"
 * )
 */
class SeriesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/series",
     *     summary="Listázza az összes sorozatot",
     *     tags={"Series"},
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres lekérés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="series",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Serie")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $series = Series::all();
        return response()->json([
            'series' => $series,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/series",
     *     summary="Új sorozat létrehozása",
     *     tags={"Series"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SerieInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sikeresen létrehozva",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="serie", ref="#/components/schemas/Serie")
     *         )
     *     )
     * )
     */
    public function store(SeriesRequest $request)
    {
        $serie = Series::create($request->all());

        return response()->json([
            'serie' => $serie,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/series/{id}",
     *     summary="Sorozat módosítása",
     *     tags={"Series"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A sorozat azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SerieInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres frissítés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="series", ref="#/components/schemas/Serie")
     *         )
     *     )
     * )
     */
    public function update(SeriesRequest $request, $id)
    {
        $serie = Series::findOrFail($id);
        $serie->update($request->all());

        return response()->json([
            'series' => $serie,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/series/{id}",
     *     summary="Sorozat törlése",
     *     tags={"Series"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A sorozat azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres törlés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Serie deleted successfully"),
     *             @OA\Property(property="id", type="integer")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $serie = Series::findOrFail($id);
        $serie->delete();
        return response()->json([
            'message' => 'Serie deleted successfully',
            'id' => $id
        ]);
    }
}

/**
 * @OA\Schema(
 *     schema="Serie",
 *     type="object",
 *     title="Sorozat adatai",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Breaking Bad"),
 *     @OA\Property(property="seasons", type="integer", example=5),
 *     @OA\Property(property="release_year", type="integer", example=2008)
 * )
 *
 * @OA\Schema(
 *     schema="SerieInput",
 *     type="object",
 *     required={"title"},
 *     @OA\Property(property="title", type="string", example="Better Call Saul"),
 *     @OA\Property(property="seasons", type="integer", example=6),
 *     @OA\Property(property="release_year", type="integer", example=2015)
 * )
 */
