<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\ClassesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classes\DeleteClassRequest;
use App\Http\Requests\Classes\GetClassesRequest;
use App\Http\Requests\Classes\StoreClassRequest;
use App\Http\Services\ClassesService;
use App\Validators\ClassesValidator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClassesController extends Controller
{
    private ClassesService $classesService;
    private ClassesValidator $classesValidator;

    public function __construct(ClassesService $classesService, ClassesValidator $classesValidator)
    {
        $this->classesService = $classesService;
        $this->classesValidator = $classesValidator;
    }

    /**
     * @OA\Get(
     *     tags={"Classes"},
     *     path="/api/classes",
     *     summary="List classes",
     *     operationId="list_classes",
     *     description="Returns a list of classes that are registered on a server",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for paginated results",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Class name",
     *         required=false,
     *         @OA\Schema(type="string", example="Cycling")
     *     ),
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date to filter between dates",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-01")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date to filter between dates",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-15")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of classes",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *     )
     * )
     */
    public function getAllClasses(GetClassesRequest $request): JsonResponse
    {
        $classesParams = ClassesHelper::withStdClass((object)$request->validated());

        return response()->json($this->classesService->getAllClasses($classesParams));
    }


    /**
     * @OA\Post(
     *     tags={"Classes"},
     *     path="/api/classes",
     *     summary="Create a new class",
     *     operationId="create_class",
     *     description="Adds a new class with the provided information.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Class information",
     *         @OA\JsonContent(
     *             required={"name", "startDate", "endDate", "capacity"},
     *             @OA\Property(property="name", type="string", example="Cycling", description="The name of the class"),
     *             @OA\Property(property="startDate", type="string", format="date", example="2023-12-01", description="The start date of the class"),
     *             @OA\Property(property="endDate", type="string", format="date", example="2023-12-15", description="The end date of the class"),
     *             @OA\Property(property="capacity", type="integer", example=30, description="The maximum capacity of the class")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Class created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid input",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *     )
     * )
     */
    public function storeClass(StoreClassRequest $request): JsonResponse
    {
        $data = ClassesHelper::withStdClass((object) $request->validated());

        $this->classesValidator->validateClassExistsFromDateInterval($data);

        return response()->json($this->classesService->addClasses($data), 201);
    }

    /**
     * @OA\Delete(
     *     tags={"Classes"},
     *     path="/api/classes",
     *     summary="Delete classes between startDate and endDate",
     *     operationId="delete_classes_by_date_range",
     *     description="Deletes all classes within the specified date range.",
     *      @OA\RequestBody(
     *         required=true,
     *         description="Class information",
     *         @OA\JsonContent(
     *             required={"startDate", "endDate"},
     *             @OA\Property(property="startDate", type="string", format="date", example="2023-12-01", description="The start date of the class"),
     *             @OA\Property(property="endDate", type="string", format="date", example="2023-12-15", description="The end date of the class"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Classes deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Classes not found within the specified date range",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid input",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *     )
     * )
     */
    public function deleteClassesByDateRange(DeleteClassRequest $request): JsonResponse
    {
        $params = ClassesHelper::withStdClass((object) $request->validated());

        $this->classesValidator->validateClassExistsForDelete($params);

        $deletedCount = $this->classesService->deleteClass($params);

        return response()->json(['message' => 'Classes deleted successfully', 'deletedCount' => $deletedCount], 200);
    }
}
