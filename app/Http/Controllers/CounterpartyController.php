<?php

namespace App\Http\Controllers;

use App\Http\Requests\Counterparties\StoreRequest;
use App\Http\Services\CounterpartyService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class CounterpartyController extends BaseController
{
    public function __construct(protected CounterpartyService $counterpartyService)
    {
    }

    /**
     *
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws ConnectionException
     * @throws ValidationException
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $counterparty = $this->counterpartyService->create($data);

        return $this->sendResponse($counterparty);
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse($this->counterpartyService->getList());
    }
}
