<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\IncomingTransaction;
use App\Models\OutgoingTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show reports dashboard.
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Show inventory report - current stock levels.
     */
    public function inventory(Request $request): View
    {
        $query = Product::where('status', 'active')->with('category', 'supplier');

        // Filter by category
        if ($request->has('category') && $request->get('category')) {
            $query->where('category_id', $request->get('category'));
        }

        $products = $query->get();

        // Calculate statistics
        $totalValue = $products->sum(function ($product) {
            return $product->quantity * $product->selling_price;
        });

        $averagePrice = $products->avg('selling_price');
        $lowStockCount = $products->filter(function ($p) {
            return $p->quantity <= $p->reorder_level;
        })->count();

        return view('reports.inventory', [
            'products' => $products,
            'totalValue' => $totalValue,
            'averagePrice' => $averagePrice,
            'lowStockCount' => $lowStockCount,
        ]);
    }

    /**
     * Show stock movement report - all movements.
     */
    public function stockMovements(Request $request): View
    {
        $query = StockMovement::with('product', 'user');

        if ($request->has('from_date') && $request->get('from_date')) {
            $query->where('created_at', '>=', $request->get('from_date'));
        }

        if ($request->has('to_date') && $request->get('to_date')) {
            $query->where('created_at', '<=', $request->get('to_date') . ' 23:59:59');
        }

        $movements = $query->latest()->get();

        $totalIn = $movements->where('type', 'in')->sum('quantity');
        $totalOut = $movements->where('type', 'out')->sum('quantity');
        $netMovement = $totalIn - $totalOut;

        $mostActiveProducts = $movements->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product' => $items->first()->product,
                    'count' => $items->count(),
                    'quantity' => $items->sum('quantity'),
                ];
            })
            ->sortByDesc('count')
            ->take(10);

        return view('reports.stock-movements', [
            'movements' => $movements,
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'netMovement' => $netMovement,
            'mostActiveProducts' => $mostActiveProducts,
        ]);
    }

    /**
     * Show sales report - from outgoing transactions.
     */
    public function sales(Request $request): View
    {
        $query = OutgoingTransaction::with('product', 'customer', 'user');

        if ($request->has('from_date') && $request->get('from_date')) {
            $query->where('transaction_date', '>=', $request->get('from_date'));
        }

        if ($request->has('to_date') && $request->get('to_date')) {
            $query->where('transaction_date', '<=', $request->get('to_date'));
        }

        $sales = $query->latest()->get();

        $totalSales = $sales->sum(function ($sale) {
            return $sale->quantity * $sale->unit_price;
        });

        $totalItems = $sales->sum('quantity');

        $topItems = $sales->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product' => $items->first()->product,
                    'quantity' => $items->sum('quantity'),
                    'total' => $items->sum(function ($item) {
                        return $item->quantity * $item->unit_price;
                    }),
                ];
            })
            ->sortByDesc('quantity')
            ->take(10);

        return view('reports.sales', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'totalItems' => $totalItems,
            'topItems' => $topItems,
        ]);
    }

    /**
     * Show monthly inventory report.
     */
    public function monthlyReport(Request $request): View
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $incomingTransactions = IncomingTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->with('product', 'supplier')
            ->get();

        $outgoingTransactions = OutgoingTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->with('product', 'customer')
            ->get();

        $totalIncoming = $incomingTransactions->sum('quantity');
        $totalIncomingValue = $incomingTransactions->sum(function ($transaction) {
            return $transaction->quantity * $transaction->unit_cost;
        });

        $totalOutgoing = $outgoingTransactions->sum('quantity');
        $totalOutgoingValue = $outgoingTransactions->sum(function ($transaction) {
            return $transaction->quantity * $transaction->unit_price;
        });

        $products = Product::where('status', 'active')->with('category')->get();

        return view('reports.monthly', [
            'month' => $month,
            'year' => $year,
            'incomingTransactions' => $incomingTransactions,
            'outgoingTransactions' => $outgoingTransactions,
            'totalIncoming' => $totalIncoming,
            'totalIncomingValue' => $totalIncomingValue,
            'totalOutgoing' => $totalOutgoing,
            'totalOutgoingValue' => $totalOutgoingValue,
            'products' => $products,
        ]);
    }
}
