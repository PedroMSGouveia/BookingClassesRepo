<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classes\DeleteClassRequest;
use App\Http\Requests\Classes\GetClassesRequest;
use App\Http\Requests\Classes\StoreClassRequest;
use App\Http\Services\ClassesService;
use App\Http\Services\UtilsService;
use Illuminate\Http\Request;

class ClassesApiController extends Controller
{
    private ClassesService $classesService;
    private UtilsService $utilsService;

    public function __construct(ClassesService $classesService, UtilsService $utilsService)
    {
        $this->classesService = $classesService;
        $this->utilsService = $utilsService;
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
    public function getAllClasses(GetClassesRequest $request)
    {
        $page = $request->input('page');

        return response()->json($this->classesService->getAllClasses($page));
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
    public function storeClass(StoreClassRequest $request)
    {
        $validatedData = $request->validated();

        return response()->json($this->classesService->addClasses($validatedData), 201);
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
    public function deleteClassesByDateRange(DeleteClassRequest $request)
    {

        $startDate = $this->utilsService->parseDate($request->validated()['startDate']);
        $endDate = $this->utilsService->parseDate($request->validated()['endDate']);

        $deletedCount = $this->classesService->deleteClass($startDate, $endDate);

        return response()->json(['message' => $deletedCount.' Classes deleted successfully'], 200);
    }
}
