<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Services\Contracts\IPlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected IPlanService $planService;

    public function __construct(IPlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * List With Filter, Search, Sort and Pagination.
     * 
     * @group Plans
     * 
     * Get a list of plans with optional filtering, searching, sorting and pagination capabilities.
     * When page and limit parameters are not provided, returns all results without pagination.
     * 
     * @queryParam page integer optional The page number for pagination. When omitted, pagination is disabled. Example: 1
     * @queryParam limit integer optional Number of items per page (max 100). When omitted, pagination is disabled. Example: 15
     * @queryParam status string optional Filter plans by status. Example: active
     * @queryParam type string optional Filter plans by type. Example: B2B, B2C
     * @queryParam keyword string optional Search keyword to filter plans by name or description. Example: premium
     * @queryParam sortField string optional Field to sort by (e.g., name, created_at, status). Example: name
     * @queryParam sortOrder string optional Sort order: asc or desc. Example: asc
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // Validate request parameters
        $request->validate([
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:100',
            'status' => 'string',
            'type' => 'string|in:B2B,B2C',
            'keyword' => 'string|max:255',
            'sortField' => 'string|in:id,name,status,created_at,updated_at',
            'sortOrder' => 'string|in:asc,desc'
        ]);

        // Extract parameters
        $page = $request->input('page');
        $limit = $request->input('limit');
        $keyword = $request->input('keyword');
        $sortField = $request->input('sortField');
        $sortOrder = $request->input('sortOrder', 'asc');

        // Build filters array
        $filters = [];
        if ($request->has('status')) {
            $filters['status'] = $request->input('status');
        }
        if ($request->has('type')) {
            $filters['type'] = $request->input('type');
        }

        // Determine if pagination is requested
        $isPaginated = $page !== null && $limit !== null;

        $plans = $this->planService->listPlans($filters, $limit, $page, $keyword, $sortField, $sortOrder);

        // Return different response format based on pagination mode
        if ($isPaginated) {
            return response()->json([
                'type'=>'SUCCESS',
                'code_status'=>200,
                'result' => PlanResource::collection($plans->items()),
                'pagination'=>[
                    'page'=>$plans->currentPage(),
                    'limit'=>$plans->perPage(),
                    'total'=>$plans->total(),
                    'totalPages'=>$plans->lastPage(),
                    'hasMore'=>$plans->hasMorePages()
                ]
            ]);
        } else {
            return response()->json([
                'type'=>'SUCCESS',
                'code_status'=>200,
                'result' => PlanResource::collection($plans)
            ]);
        }
    }
    /**
     * List With Filter, Search, Sort and Pagination [public].
     * @unauthenticated
     * @group Plans
     * 
     * Get a list of plans with optional filtering, searching, sorting and pagination capabilities.
     * When page and limit parameters are not provided, returns all results without pagination.
     * 
     * @queryParam page integer optional The page number for pagination. When omitted, pagination is disabled. Example: 1
     * @queryParam limit integer optional Number of items per page (max 100). When omitted, pagination is disabled. Example: 15
     * @queryParam status string optional Filter plans by status. Example: active
     * @queryParam type string optional Filter plans by type. Example: B2B, B2C
     * @queryParam keyword string optional Search keyword to filter plans by name or description. Example: premium
     * @queryParam sortField string optional Field to sort by (e.g., name, created_at, status). Example: name
     * @queryParam sortOrder string optional Sort order: asc or desc. Example: asc
     *
     * @param Request $request
     */
    public function indexPublic(Request $request)
    {
        // Validate request parameters
        $request->validate([
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:100',
            'status' => 'string',
            'type' => 'string|in:B2B,B2C',
            'keyword' => 'string|max:255',
            'sortField' => 'string|in:id,name,status,created_at,updated_at',
            'sortOrder' => 'string|in:asc,desc'
        ]);

        // Extract parameters
        $page = $request->input('page');
        $limit = $request->input('limit');
        $keyword = $request->input('keyword');
        $sortField = $request->input('sortField');
        $sortOrder = $request->input('sortOrder', 'asc');

        // Build filters array
        $filters = [];
        if ($request->has('status')) {
            $filters['status'] = $request->input('status');
        }
        if ($request->has('type')) {
            $filters['type'] = $request->input('type');
        }

        // Determine if pagination is requested
        $isPaginated = $page !== null && $limit !== null;

        $plans = $this->planService->listPlans($filters, $limit, $page, $keyword, $sortField, $sortOrder);

        // Return different response format based on pagination mode
        if ($isPaginated) {
            return response()->json([
                'type'=>'SUCCESS',
                'code_status'=>200,
                'result' => PlanResource::collection($plans->items()),
                'pagination'=>[
                    'page'=>$plans->currentPage(),
                    'limit'=>$plans->perPage(),
                    'total'=>$plans->total(),
                    'totalPages'=>$plans->lastPage(),
                    'hasMore'=>$plans->hasMorePages()
                ]
            ]);
        } else {
            return response()->json([
                'type'=>'SUCCESS',
                'code_status'=>200,
                'result' => PlanResource::collection($plans)
            ]);
        }
    }
}