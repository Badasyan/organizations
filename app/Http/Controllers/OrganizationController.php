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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="МКК МорФинансСантех"),
     *                 @OA\Property(
     *                     property="phone_numbers",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"1-848-254-3223","602.408.4938"}
     *                 ),
     *                 @OA\Property(property="building_id", type="integer", example=15),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z")
     *             )
     *         )
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
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=5),
     *             @OA\Property(property="name", type="string", example="ООО Компания CибРечОбл"),
     *             @OA\Property(
     *                 property="phone_numbers",
     *                 type="array",
     *                 description="Массив телефонных номеров",
     *                 @OA\Items(type="string"),
     *                 example={"1-239-920-9117", "541.635.8314"}
     *             ),
     *             @OA\Property(property="building_id", type="integer", example=33),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *
     *             @OA\Property(
     *                 property="building",
     *                 type="object",
     *                 description="Данные о здании",
     *                 @OA\Property(property="id", type="integer", example=33),
     *                 @OA\Property(property="address", type="string", example="573466, Оренбургская область, город Дмитров, въезд Чехова, 91"),
     *                 @OA\Property(property="latitude", type="string", example="-4.9280140"),
     *                 @OA\Property(property="longitude", type="string", example="4.4754080"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z")
     *             ),
     *
     *             @OA\Property(
     *                 property="activities",
     *                 type="array",
     *                 description="Список видов деятельности, связанных с организацией",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=26),
     *                     @OA\Property(property="name", type="string", example="Мебель"),
     *                     @OA\Property(property="parent_id", type="integer", example=3),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                     @OA\Property(
     *                         property="pivot",
     *                         type="object",
     *                         @OA\Property(property="organization_id", type="integer", example=5),
     *                         @OA\Property(property="activity_id", type="integer", example=26)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"error": "Организация не найдена"}
     *         )
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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=14),
     *                 @OA\Property(property="name", type="string", example="ООО Компания ЛифтОбл"),
     *                 @OA\Property(
     *                     property="phone_numbers",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"623.336.2503","1-781-212-8873"}
     *                 ),
     *                 @OA\Property(property="building_id", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Здание не найдено",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"error": "Здание не найдено"}
     *         )
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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=28),
     *                 @OA\Property(property="name", type="string", example="МФО ТяжТеле"),
     *                 @OA\Property(
     *                     property="phone_numbers",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"631-762-4660","+1-872-456-2948"}
     *                 ),
     *                 @OA\Property(property="building_id", type="integer", example=6),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Вид деятельности не найден",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"error": "Вид деятельности не найден"}
     *         )
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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=6),
     *                 @OA\Property(property="name", type="string", example="ПАО МеталОблМикро"),
     *                 @OA\Property(
     *                     property="phone_numbers",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"612.204.1583", "248-613-9276"}
     *                 ),
     *                 @OA\Property(property="building_id", type="integer", example=48),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"error": "Организация не найдена"}
     *         )
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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=14),
     *                 @OA\Property(property="name", type="string", example="ООО Компания ЛифтОбл"),
     *                 @OA\Property(
     *                     property="phone_numbers",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"623.336.2503","1-781-212-8873"}
     *                 ),
     *                 @OA\Property(property="building_id", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="error", type="string", example="Некорректные данные"),
     *                 @OA\Property(property="details", type="array", @OA\Items(type="string"))
     *             }
     *         )
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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=28),
     *                 @OA\Property(property="name", type="string", example="МФО ТяжТеле"),
     *                 @OA\Property(
     *                     property="phone_numbers",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"631-762-4660", "+1-872-456-2948"}
     *                 ),
     *                 @OA\Property(property="building_id", type="integer", example=6),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *
     *                 @OA\Property(
     *                     property="activities",
     *                     type="array",
     *                     description="Массив связанных видов деятельности",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="name", type="string", example="Компьютеры"),
     *                         @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                         @OA\Property(
     *                             property="pivot",
     *                             type="object",
     *                             @OA\Property(property="organization_id", type="integer", example=28),
     *                             @OA\Property(property="activity_id", type="integer", example=3)
     *                         )
     *                     )
     *                 ),
     *
     *                 @OA\Property(
     *                     property="building",
     *                     type="object",
     *                     description="Данные о здании",
     *                     @OA\Property(property="id", type="integer", example=6),
     *                     @OA\Property(property="address", type="string", example="143831, Смоленская область, город Подольск, пер. Косиора, 23"),
     *                     @OA\Property(property="latitude", type="string", example="-35.1403850"),
     *                     @OA\Property(property="longitude", type="string", example="-91.7947170"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T19:23:50.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация с таким видом деятельности не найдена",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"error": "Организация с таким видом деятельности не найдена"}
     *         )
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
