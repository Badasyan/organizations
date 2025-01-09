<?php
namespace App\Http\Controllers;

use App\Actions\Organization\GetAllOrganizationAction;
use App\Actions\Organization\GetOrganizationByBuildingAction;
use App\Actions\Organization\GetOrganizationByActivityAction;
use App\Actions\Organization\GetOrganizationByIdAction;
use App\Actions\Organization\GetOrganizationsByRadiusAction;
use App\Actions\Organization\SearchActivitiesNameAction;
use App\Actions\Organization\SearchOrganizationAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Throwable;

/**
 * @OA\Info(title="Organizations API", version="0.1")
 */
class OrganizationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/organizations",
     *     summary="Получить список всех организаций",
     *     tags={"Организации"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index(GetAllOrganizationAction $action): JsonResponse
    {
        try {
            return response()->json($action->execute());
        } catch (Throwable $e) {
            return response()->json(['error' => 'Ошибка получения данных: ' . $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Получить информацию об организации по ID",
     *     tags={"Организации"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID организации",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена"
     *     )
     * )
     */
    public function show(GetOrganizationByIdAction $action, $id): JsonResponse
    {
        try {
            return response()->json($action->execute($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Организация не найдена'], 404);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/building/{buildingId}",
     *     summary="Получить список организаций в указанном здании",
     *     tags={"Организации"},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         description="ID здания",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function byBuilding(GetOrganizationByBuildingAction $action, $buildingId): JsonResponse
    {
        try {
            return response()->json($action->execute($buildingId));
        } catch (Throwable $e) {
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity/{activityId}",
     *     summary="Получить список организаций по виду деятельности",
     *     tags={"Организации"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function byActivity(GetOrganizationByActivityAction $action, $activityId): JsonResponse
    {
        try {
            return response()->json($action->execute($activityId));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Вид деятельности не найден'], 404);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/search/{name}",
     *     summary="Поиск организаций по названию",
     *     tags={"Организации"},
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         description="Название организации",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function search(SearchOrganizationAction $action, $name): JsonResponse
    {
        try {
            return response()->json($action->execute($name));
        } catch (Throwable $e) {
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/radius",
     *     summary="Поиск организаций в заданном радиусе",
     *     tags={"Организации"},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         required=true,
     *         description="Широта точки центра",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         required=true,
     *         description="Долгота точки центра",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=true,
     *         description="Радиус поиска в километрах",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(type="object", properties={
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="details", type="array", @OA\Items(type="string"))
     *         })
     *     )
     * )
     */
    public function byRadius(GetOrganizationsByRadiusAction $action, Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'radius' => 'required|numeric|min:0'
            ]);

            return response()->json($action->execute(
                $validated['latitude'],
                $validated['longitude'],
                $validated['radius']
            ));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Некорректные данные', 'details' => $e->errors()], 422);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/searchActivityName/{activityName}",
     *     summary="Поиск организаций по названию вида деятельности",
     *     tags={"Организации"},
     *     @OA\Parameter(
     *         name="activityName",
     *         in="path",
     *         required=true,
     *         description="Название вида деятельности",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function byActivityName(SearchActivitiesNameAction $action, $activityName): JsonResponse
    {
        try {
            return response()->json($action->execute($activityName));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Вид деятельности не найден'], 404);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }
}
