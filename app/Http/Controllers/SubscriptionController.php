<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
    private string $apiUrl = "http://127.0.0.1:8001/api/subscriptions";

    // Menambahkan URL API relasi untuk data Dropdown form
    private string $customerApiUrl = "http://127.0.0.1:8001/api/customers";
    private string $serviceApiUrl = "http://127.0.0.1:8001/api/services";

    public function index(Request $request): View
    {
        $query = [];
        if ($request->has("status")) {
            $query["status"] = $request->status;
        }

        $response = Http::get($this->apiUrl, $query);
        $subscriptions = $response->successful() ? $response->json("data") : [];

        $customerResponse = Http::get($this->customerApiUrl);
        $customers = $customerResponse->successful() ? $customerResponse->json("data") : [];

        $serviceResponse = Http::get($this->serviceApiUrl);
        $services = $serviceResponse->successful() ? $serviceResponse->json("data") : [];

        return view("subscriptions.index", [
            "active" => "subscriptions",
            "subscriptions" => $subscriptions,
            "customers" => $customers,
            "services" => $services,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $response = Http::post($this->apiUrl, [
            "customer_id" => $request->customer_id,
            "service_id" => $request->service_id,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "status" => $request->status,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route("subscriptions.index")
                ->with("toast_success", $response->json("message"));
        }

        if ($response->status() === 422) {
            return back()
                ->withErrors($response->json("errors") ?? [])
                ->withInput()
                ->with("toast_error", $response->json("message"))
                ->with("open_modal", "addDataModal");
        }

        return back()
            ->withInput()
            ->with(
                "toast_error",
                $response->json("message") ?? "Something went wrong",
            );
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $response = Http::patch("{$this->apiUrl}/{$id}", [
            "customer_id" => $request->customer_id,
            "service_id" => $request->service_id,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "status" => $request->status,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route("subscriptions.index")
                ->with("toast_success", $response->json("message"));
        }

        if ($response->status() === 422) {
            return back()
                ->withErrors($response->json("errors") ?? [])
                ->withInput()
                ->with("toast_error", $response->json("message"))
                ->with("open_modal", "editDataModal")
                ->with("edit_subscription_id", $id);
        }

        return back()
            ->withInput()
            ->with(
                "toast_error",
                $response->json("message") ?? "Something went wrong",
            );
    }

    public function destroy(int $id): RedirectResponse
    {
        $response = Http::delete("{$this->apiUrl}/{$id}");

        if ($response->successful()) {
            return redirect()
                ->route("subscriptions.index")
                ->with("toast_success", $response->json("message"));
        }

        return back()->with(
            "toast_error",
            $response->json("message") ?? "Something went wrong",
        );
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        // Berdasarkan route API: patch("subscriptions/{subscription}/status")
        $response = Http::patch("{$this->apiUrl}/{$id}/status", [
            "status" => $request->status
        ]);

        if ($response->successful()) {
            return redirect()
                ->route("subscriptions.index")
                ->with("toast_success", $response->json("message"));
        }

        return back()->with(
            "toast_error",
            $response->json("message") ?? "Something went wrong",
        );
    }
}