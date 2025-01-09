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
     *                 example={
     *                     {
     *                         "id": 1,
     *                         "name": "МКК МорФинансСантех",
     *                         "phone_numbers": "[\"1-848-254-3223\", \"602.408.4938\"]",
     *                         "building_id": 15,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "name": "ОАО Cиб",
     *                         "phone_numbers": "[\"+1.757.905.0088\", \"(631) 507-1240\"]",
     *                         "building_id": 8,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     }
     *                 }
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
     *             example={
     *                 "id": 5,
     *                 "name": "ООО Компания CибРечОбл",
     *                 "phone_numbers": "[\"1-239-920-9117\", \"541.635.8314\"]",
     *                 "building_id": 33,
     *                 "created_at": "2025-01-09T19:23:50.000000Z",
     *                 "updated_at": "2025-01-09T19:23:50.000000Z",
     *                 "building": {
     *                     "id": 33,
     *                     "address": "573466, Оренбургская область, город Дмитров, въезд Чехова, 91",
     *                     "latitude": "-4.9280140",
     *                     "longitude": "4.4754080",
     *                     "created_at": "2025-01-09T19:23:50.000000Z",
     *                     "updated_at": "2025-01-09T19:23:50.000000Z"
     *                 },
     *                 "activities": [
     *                     {
     *                         "id": 26,
     *                         "name": "Мебель",
     *                         "parent_id": 3,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z",
     *                         "pivot": {
     *                             "organization_id": 5,
     *                             "activity_id": 26
     *                         }
     *                     },
     *                     {
     *                         "id": 38,
     *                         "name": "Туризм",
     *                         "parent_id": 34,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z",
     *                         "pivot": {
     *                             "organization_id": 5,
     *                             "activity_id": 38
     *                         }
     *                     },
     *                     {
     *                         "id": 33,
     *                         "name": "Домашний текстиль",
     *                         "parent_id": 4,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z",
     *                         "pivot": {
     *                             "organization_id": 5,
     *                             "activity_id": 33
     *                         }
     *                     }
     *                 ]
     *             }
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
     *                 example={
     *                     {
     *                         "id": 14,
     *                         "name": "ООО Компания ЛифтОбл",
     *                         "phone_numbers": "[\"623.336.2503\", \"1-781-212-8873\"]",
     *                         "building_id": 3,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     },
     *                     {
     *                         "id": 25,
     *                         "name": "ОАО ТрансОрионКрепОпт",
     *                         "phone_numbers": "[\"248-615-1640\", \"+1.352.783.2345\"]",
     *                         "building_id": 3,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     },
     *                     {
     *                         "id": 29,
     *                         "name": "МФО ТехХмельТех",
     *                         "phone_numbers": "[\"1-941-503-0263\", \"346.461.0052\"]",
     *                         "building_id": 3,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     }
     *                 }
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
     *                 example={
     *                     {
     *                         "id": 28,
     *                         "name": "МФО ТяжТеле",
     *                         "phone_numbers": "[\"631-762-4660\", \"+1-872-456-2948\"]",
     *                         "building_id": 6,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     },
     *                     {
     *                         "id": 48,
     *                         "name": "ООО Компания ДизайнАсбоцементВод",
     *                         "phone_numbers": "[\"+19797352329\", \"(940) 780-8399\"]",
     *                         "building_id": 35,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     }
     *                 }
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
     *                 example={
     *                     {
     *                         "id": 6,
     *                         "name": "ПАО МеталОблМикро",
     *                         "phone_numbers": "[\"612.204.1583\", \"248-613-9276\"]",
     *                         "building_id": 48,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     }
     *                 }
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
     *                 example={
     *                     {
     *                         "id": 14,
     *                         "name": "ООО Компания ЛифтОбл",
     *                         "phone_numbers": "[\"623.336.2503\", \"1-781-212-8873\"]",
     *                         "building_id": 3,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     },
     *                     {
     *                         "id": 25,
     *                         "name": "ОАО ТрансОрионКрепОпт",
     *                         "phone_numbers": "[\"248-615-1640\", \"+1.352.783.2345\"]",
     *                         "building_id": 3,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     },
     *                     {
     *                         "id": 29,
     *                         "name": "МФО ТехХмельТех",
     *                         "phone_numbers": "[\"1-941-503-0263\", \"346.461.0052\"]",
     *                         "building_id": 3,
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     }
     *                 }
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
     *                 example={
     *                     "id": 28,
     *                     "name": "МФО ТяжТеле",
     *                     "phone_numbers": "[\"631-762-4660\", \"+1-872-456-2948\"]",
     *                     "building_id": 6,
     *                     "created_at": "2025-01-09T19:23:50.000000Z",
     *                     "updated_at": "2025-01-09T19:23:50.000000Z",
     *                     "activities": {
     *                         {
     *                             "id": 3,
     *                             "name": "Компьютеры",
     *                             "parent_id": null,
     *                             "created_at": "2025-01-09T19:23:50.000000Z",
     *                             "updated_at": "2025-01-09T19:23:50.000000Z",
     *                             "pivot": {
     *                                 "organization_id": 28,
     *                                 "activity_id": 3
     *                             }
     *                         },
     *                         {
     *                             "id": 7,
     *                             "name": "Инструменты",
     *                             "parent_id": 1,
     *                             "created_at": "2025-01-09T19:23:50.000000Z",
     *                             "updated_at": "2025-01-09T19:23:50.000000Z",
     *                             "pivot": {
     *                                 "organization_id": 28,
     *                                 "activity_id": 7
     *                             }
     *                         },
     *                         {
     *                             "id": 11,
     *                             "name": "Мобильные устройства",
     *                             "parent_id": 7,
     *                             "created_at": "2025-01-09T19:23:50.000000Z",
     *                             "updated_at": "2025-01-09T19:23:50.000000Z",
     *                             "pivot": {
     *                                 "organization_id": 28,
     *                                 "activity_id": 11
     *                             }
     *                         }
     *                     },
     *                     "building": {
     *                         "id": 6,
     *                         "address": "143831, Смоленская область, город Подольск, пер. Косиора, 23",
     *                         "latitude": "-35.1403850",
     *                         "longitude": "-91.7947170",
     *                         "created_at": "2025-01-09T19:23:50.000000Z",
     *                         "updated_at": "2025-01-09T19:23:50.000000Z"
     *                     }
     *                 }
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
