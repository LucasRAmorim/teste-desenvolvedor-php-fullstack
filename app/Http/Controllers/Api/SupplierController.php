<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Interfaces\SupplierRepositoryInterface;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\SupplierResource;
use Illuminate\Http\JsonResponse;


class SupplierController extends Controller
{
    public function __construct(
        protected SupplierRepositoryInterface $supplierRepository
    ) {}

    public function index(Request $request)
    {
        $data = $this->supplierRepository->all($request);
        return SupplierResource::collection($data);
    }


    public function store(StoreSupplierRequest $request): JsonResponse
    {
        try {
            $supplier = $this->supplierRepository->create($request->validated());
            return response()->json([
                'message' => 'Supplier created successfully.',
                'data' => new SupplierResource($supplier)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating supplier.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }


    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        try {
            $updated = $this->supplierRepository->update($supplier, $request->validated());

            return response()->json([
                'message' => 'Supplier updated successfully.',
                'data' => new SupplierResource($updated)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating supplier.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(Supplier $supplier): JsonResponse
    {
        try {
            $this->supplierRepository->delete($supplier);
            return response()->json(['message' => 'Supplier deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting supplier.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function fetchCNPJ($cnpj)
    {
        $response = Http::get("https://brasilapi.com.br/api/cnpj/v1/$cnpj");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'CNPJ not found'], 404);
    }
}
