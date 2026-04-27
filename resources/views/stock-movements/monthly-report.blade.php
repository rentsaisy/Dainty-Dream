@extends('layouts.app')

@section('page-title', 'Monthly Report')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <!-- Header with Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="margin: 0; font-size: 32px; font-weight: 700; color: #7C6BA8;">Monthly Report</h1>
            <p style="margin: 8px 0 0 0; color: #9F8FBF; font-size: 16px;">{{ $currentMonth->format('F Y') }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('report.monthly') }}?month={{ $currentMonth->copy()->subMonth()->format('Y-m-d') }}" 
               style="padding: 10px 16px; border: 2px solid #E8D7FF; background: white; color: #7C6BA8; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                ← Previous
            </a>
            <button onclick="window.print()" 
                    style="padding: 10px 16px; background: #D4BAFF; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                🖨 Print
            </button>
            <a href="{{ route('report.monthly') }}?month={{ $currentMonth->copy()->addMonth()->format('Y-m-d') }}" 
               style="padding: 10px 16px; border: 2px solid #E8D7FF; background: white; color: #7C6BA8; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                Next →
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    @php
        $netMovement = $incomingTotal - $outgoingTotal;
        $isPositive = $netMovement > 0;
    @endphp
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <!-- Incoming Card -->
        <div style="background: linear-gradient(135deg, rgba(180, 231, 255, 0.15) 0%, rgba(200, 220, 255, 0.1) 100%); border: 2px solid rgba(180, 231, 255, 0.3); border-radius: 12px; padding: 24px; transition: all 0.3s ease;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0 0 8px 0; color: #0369a1; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Incoming Goods</p>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #0369a1;">{{ $incomingCount }}</p>
                    <p style="margin: 12px 0 0 0; font-size: 18px; font-weight: 600; color: #0369a1;">${{ number_format($incomingTotal, 2) }}</p>
                </div>
                <div style="font-size: 40px;">📦</div>
            </div>
        </div>

        <!-- Outgoing Card -->
        <div style="background: linear-gradient(135deg, rgba(212, 186, 255, 0.15) 0%, rgba(230, 210, 255, 0.1) 100%); border: 2px solid rgba(212, 186, 255, 0.3); border-radius: 12px; padding: 24px; transition: all 0.3s ease;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0 0 8px 0; color: #6d28d9; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Outgoing Goods</p>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #6d28d9;">{{ $outgoingCount }}</p>
                    <p style="margin: 12px 0 0 0; font-size: 18px; font-weight: 600; color: #6d28d9;">${{ number_format($outgoingTotal, 2) }}</p>
                </div>
                <div style="font-size: 40px;">📤</div>
            </div>
        </div>

        <!-- Net Movement Card - Positive -->
        @if($isPositive)
        <div style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(74, 222, 128, 0.1) 100%); border: 2px solid rgba(34, 197, 94, 0.3); border-radius: 12px; padding: 24px; transition: all 0.3s ease;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0 0 8px 0; color: #15803d; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Net Movement</p>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #15803d;">{{ $incomingCount - $outgoingCount }}</p>
                    <p style="margin: 12px 0 0 0; font-size: 18px; font-weight: 600; color: #15803d;">+${{ number_format($netMovement, 2) }}</p>
                </div>
                <div style="font-size: 40px;">📈</div>
            </div>
        </div>
        @else
        <!-- Net Movement Card - Negative -->
        <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(248, 113, 113, 0.1) 100%); border: 2px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 24px; transition: all 0.3s ease;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0 0 8px 0; color: #7f1d1d; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Net Movement</p>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #7f1d1d;">{{ $incomingCount - $outgoingCount }}</p>
                    <p style="margin: 12px 0 0 0; font-size: 18px; font-weight: 600; color: #7f1d1d;">${{ number_format($netMovement, 2) }}</p>
                </div>
                <div style="font-size: 40px;">📉</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Incoming Transactions Section -->
    <div style="margin-bottom: 40px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #E8D7FF;">
            <span style="font-size: 24px;">📦</span>
            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #7C6BA8;">Incoming Transactions</h2>
            <span style="margin-left: auto; background: rgba(180, 231, 255, 0.2); color: #0369a1; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                {{ $incomingTransactions->count() }} items
            </span>
        </div>
        
        @if($incomingTransactions->count() > 0)
            <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #E8D7FF; box-shadow: 0 4px 12px rgba(124, 107, 168, 0.08);">
                <table style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr style="background: linear-gradient(to right, #F5E6FF 0%, #F0E8FF 100%); border-bottom: 2px solid #E8D7FF;">
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Supplier</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Quantity</th>
                            <th style="padding: 16px; text-align: right; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Unit Price</th>
                            <th style="padding: 16px; text-align: right; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Total</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incomingTransactions as $transaction)
                            <tr style="border-bottom: 1px solid #F0E8FF; transition: all 0.2s ease; background: white;">
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px;">{{ $transaction->supplier->name ?? 'N/A' }}</td>
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px; font-weight: 500;">{{ $transaction->product->name ?? 'N/A' }}</td>
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px; text-align: center;">
                                    <span style="background: rgba(180, 231, 255, 0.2); padding: 4px 10px; border-radius: 6px; color: #0369a1; font-weight: 600;">
                                        {{ $transaction->quantity }} units
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px; text-align: right;">${{ number_format($transaction->price, 2) }}</td>
                                <td style="padding: 14px 16px; color: #0369a1; font-size: 14px; font-weight: 600; text-align: right;">${{ number_format($transaction->quantity * $transaction->price, 2) }}</td>
                                <td style="padding: 14px 16px; color: #64748b; font-size: 14px;">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, rgba(245, 230, 255, 0.5) 0%, rgba(232, 215, 255, 0.3) 100%); border-radius: 10px; border: 2px dashed #E8D7FF;">
                <p style="margin: 0; font-size: 48px;">📭</p>
                <p style="margin: 12px 0 0 0; color: #9F8FBF; font-size: 16px; font-weight: 500;">No incoming transactions for {{ $currentMonth->format('F Y') }}</p>
            </div>
        @endif
    </div>

    <!-- Outgoing Transactions Section -->
    <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #E8D7FF;">
            <span style="font-size: 24px;">📤</span>
            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #7C6BA8;">Outgoing Transactions</h2>
            <span style="margin-left: auto; background: rgba(212, 186, 255, 0.2); color: #6d28d9; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                {{ $outgoingTransactions->count() }} items
            </span>
        </div>
        
        @if($outgoingTransactions->count() > 0)
            <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #E8D7FF; box-shadow: 0 4px 12px rgba(124, 107, 168, 0.08);">
                <table style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr style="background: linear-gradient(to right, #F5E6FF 0%, #F0E8FF 100%); border-bottom: 2px solid #E8D7FF;">
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Customer</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Quantity</th>
                            <th style="padding: 16px; text-align: right; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Unit Price</th>
                            <th style="padding: 16px; text-align: right; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Total</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #7C6BA8; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outgoingTransactions as $transaction)
                            <tr style="border-bottom: 1px solid #F0E8FF; transition: all 0.2s ease; background: white;">
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px;">{{ $transaction->customer->name ?? 'N/A' }}</td>
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px; font-weight: 500;">{{ $transaction->product->name ?? 'N/A' }}</td>
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px; text-align: center;">
                                    <span style="background: rgba(212, 186, 255, 0.2); padding: 4px 10px; border-radius: 6px; color: #6d28d9; font-weight: 600;">
                                        {{ $transaction->quantity }} units
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; color: #374151; font-size: 14px; text-align: right;">${{ number_format($transaction->price, 2) }}</td>
                                <td style="padding: 14px 16px; color: #6d28d9; font-size: 14px; font-weight: 600; text-align: right;">${{ number_format($transaction->quantity * $transaction->price, 2) }}</td>
                                <td style="padding: 14px 16px; color: #64748b; font-size: 14px;">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, rgba(245, 230, 255, 0.5) 0%, rgba(232, 215, 255, 0.3) 100%); border-radius: 10px; border: 2px dashed #E8D7FF;">
                <p style="margin: 0; font-size: 48px;">📭</p>
                <p style="margin: 12px 0 0 0; color: #9F8FBF; font-size: 16px; font-weight: 500;">No outgoing transactions for {{ $currentMonth->format('F Y') }}</p>
            </div>
        @endif
    </div>
</div>

<style>
    @media print {
        button, a {
            display: none;
        }
        
        body {
            background: white;
        }
    }
    
    @media (max-width: 768px) {
        h1 {
            font-size: 24px !important;
        }
        
        h2 {
            font-size: 18px !important;
        }
        
        table {
            font-size: 12px !important;
        }
        
        td, th {
            padding: 10px 8px !important;
        }
    }
</style>
@endsection
