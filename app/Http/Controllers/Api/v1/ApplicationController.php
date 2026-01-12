<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Application\OnHoldRequest;
use App\Http\Requests\Api\v1\Application\RejectRequest;
use App\Http\Requests\Api\v1\Application\StoreRequest;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class ApplicationController extends Controller
{
    public function __construct(protected ApplicationService $applicationService)
    {
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $status = $request->get('status');
        $cacheKeys = ['applications'];

        $query = Application::query()->with(['guardian', 'student']);

        if (!empty($search)) {
            $cacheKeys[] = $search;
            $query
                ->whereHas('guardian', function (Builder $query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('user.email', 'like', "{$search}");
                })->orWhereHas('student', function (Builder $query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "{$search}");
                });
        }

        if (!empty($status)) {
            $cacheKeys[] = $status;
            $query->where('status', $status);
        }

        // Add user to cache key
        $cacheKeys[] = 'user-' . auth()->user()->id;

        $applications = \Cache::remember(implode(':', $cacheKeys), 600, function () use ($query, $perPage, $page) {
            return $query->paginate($perPage, ['*'], 'page', $page);

        });

        return response()->json($applications);
    }

    public function store(StoreRequest $request)
    {
        try {
            $application = $this->applicationService->new(
                $request->get('guardian_first_name'),
                $request->get('guardian_last_name'),
                $request->get('guardian_email'),
                $request->get('guardian_contact_number'),
                $request->get('student_first_name'),
                $request->get('student_last_name'),
                $request->get('student_birth_date'),
                $request->get('guardian_relationship'),
            );

            return response()->json($application);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }

        return response()->json([
            'error' => $message
        ], 500);
    }

    public function statusProcess($id)
    {
        $application = Application::find($id);

        if (empty($application)) {
            return response()->json([
                'message' => 'Application not found.'
            ], 404);
        }

        try {
            $this->applicationService->use($application)->process();

            return response()->json([
                'message' => 'Application is now being processed.'
            ]);
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }

        return response()->json([
            'error' => $error
        ], 500);
    }

    public function statusOnHold(OnHoldRequest $request, $id)
    {
        $application = Application::find($id);
        $remarks = $request->get('remarks');

        if (empty($application)) {
            return response()->json([
                'message' => 'Application not found.'
            ], 404);
        }

        try {
            $this->applicationService->use($application)->hold($remarks);

            return response()->json([
                'message' => 'Application is now on hold.'
            ]);
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }

        return response()->json([
            'error' => $error
        ], 500);
    }

    public function statusReject(RejectRequest $request, $id)
    {
        $application = Application::find($id);
        $remarks = $request->get('remarks');

        if (empty($application)) {
            return response()->json([
                'message' => 'Application not found.'
            ], 404);
        }

        try {
            $this->applicationService->use($application)->reject($remarks);

            return response()->json([
                'message' => 'Application is now on hold.'
            ]);
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }

        return response()->json([
            'error' => $error
        ], 500);
    }

    public function statusAccept($id)
    {
        $application = Application::find($id);

        if (empty($application)) {
            return response()->json([
                'message' => 'Application not found.'
            ], 404);
        }

        try {
            $this->applicationService->use($application)->accept();

            return response()->json([
                'message' => 'Application is now accepted'
            ]);
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }

        return response()->json([
            'error' => $error
        ], 500);
    }
}
