<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Buyer;
use App\Models\Seller;
use App\Models\PurchaseContract;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Vehicle statistics
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'Available')->count();
        $soldVehicles = Vehicle::where('status', 'Sold')->count();
        $reservedVehicles = Vehicle::where('status', 'Reserved')->count();

        // Sales statistics
        $totalSales = PurchaseContract::count();
        $monthlySales = PurchaseContract::whereMonth('contract_date', now()->month)
            ->whereYear('contract_date', now()->year)
            ->count();

        // Calculate total revenue
        $totalRevenue = Vehicle::where('status', 'Sold')
            ->sum('selling_price');

        // Calculate monthly revenue
        $monthlyRevenue = DB::table('purchase_contracts')
            ->join('vehicles', 'purchase_contracts.vin', '=', 'vehicles.vin')
            ->whereMonth('purchase_contracts.contract_date', now()->month)
            ->whereYear('purchase_contracts.contract_date', now()->year)
            ->sum('vehicles.selling_price');

        // Calculate profit (selling_price - purchase_price)
        $totalProfit = DB::table('vehicles')
            ->where('status', 'Sold')
            ->sum(DB::raw('selling_price - purchase_price'));

        // Top selling models
        $topModels = DB::table('vehicles')
            ->join('vehicle_models', 'vehicles.model_id', '=', 'vehicle_models.model_id')
            ->join('manufacturers', 'vehicles.manufacturer_id', '=', 'manufacturers.manufacturer_id')
            ->where('vehicles.status', 'Sold')
            ->select('vehicle_models.model_name', 'manufacturers.name as manufacturer_name', DB::raw('count(*) as count'))
            ->groupBy('vehicle_models.model_name', 'manufacturers.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Top selling employees
        $topEmployees = DB::table('purchase_contracts')
            ->join('employees', 'purchase_contracts.employee_id', '=', 'employees.employee_id')
            ->select(
                'employees.employee_id',
                'employees.first_name',
                'employees.last_name',
                DB::raw('count(*) as sales_count'),
                DB::raw('sum(vehicles.selling_price) as total_sales')
            )
            ->join('vehicles', 'purchase_contracts.vin', '=', 'vehicles.vin')
            ->groupBy('employees.employee_id', 'employees.first_name', 'employees.last_name')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        // Inventory age statistics
        $inventoryAgeStats = [
            '0-30 days' => Vehicle::where('status', 'Available')
                ->where('days_on_stock', '<=', 30)
                ->count(),
            '31-60 days' => Vehicle::where('status', 'Available')
                ->where('days_on_stock', '>', 30)
                ->where('days_on_stock', '<=', 60)
                ->count(),
            '61-90 days' => Vehicle::where('status', 'Available')
                ->where('days_on_stock', '>', 60)
                ->where('days_on_stock', '<=', 90)
                ->count(),
            '90+ days' => Vehicle::where('status', 'Available')
                ->where('days_on_stock', '>', 90)
                ->count(),
        ];

        // Customer statistics
        $totalBuyers = Buyer::count();
        $totalSellers = Seller::count();

        // Recent transactions
        $recentTransactions = PurchaseContract::with(['vehicle', 'buyer', 'employee'])
            ->orderBy('contract_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalVehicles',
            'availableVehicles',
            'soldVehicles',
            'reservedVehicles',
            'totalSales',
            'monthlySales',
            'totalRevenue',
            'monthlyRevenue',
            'totalProfit',
            'topModels',
            'topEmployees',
            'inventoryAgeStats',
            'totalBuyers',
            'totalSellers',
            'recentTransactions'
        ));
    }
}
