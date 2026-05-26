<?php

namespace App\Http\Services\Dashboard\Reports;

trait LossProfitService
{
    use TrailBalanceService;

    public function calculateTradingAccountData($request)
    {
        $groups = [7, 14, 15]; // Direct Expenses, Sales account and Purchase account

        $calculatedData = $this->calculateGroupTotalsWithChildrenLedgersAndSubGroups($request, [], $groups);

        $overallCredit = $calculatedData['overall_total_credit'];
        $overallDebit = $calculatedData['overall_total_debit'];
        $result = $calculatedData['groups'];
        $showOpening = $calculatedData['showOpening'];
        $showClosing = $calculatedData['showClosing'];
        $showTransactions = $calculatedData['showTransactions'];
        $nettTransactions = $calculatedData['nettTransactions'];
        $detailedView = $calculatedData['detailedView'];

        // Calculate Gross Profit or Gross Loss
        $grossCredit = null;
        $grossDebit = null;

        if ($overallCredit > $overallDebit) {
            $grossCredit = $overallCredit - $overallDebit;
        } elseif ($overallDebit > $overallCredit) {
            $grossDebit = $overallDebit - $overallCredit;
        }
        elseif ($overallDebit == $overallCredit) {
            $grossDebit = 0;
            $grossCredit = 0;
        }

//        return $result;

        $openingStock = 0;
        $closingStock = 0;

        $purchaseTotal = collect($result)->where('group_id', 15)->sum(fn($g) => $g['totals']['closing_balance']);
        $directExpensesTotal = collect($result)->where('group_id', 7)->sum(fn($g) => $g['totals']['closing_balance']);
        $salesTotal = collect($result)->where('group_id', 14)->sum(fn($g) => $g['totals']['closing_balance']);

        // ✅ Cost of Sales = Opening + Purchases - Closing
        $costOfSales = $openingStock + $purchaseTotal - $closingStock;

        // ✅ Gross Profit/Loss = Sales - (Cost of Sales + Direct Expenses)
        $grossResult = $salesTotal - ($costOfSales + $directExpensesTotal);

//        return $result;

        return [
            'groups' => $result,
            'openingStock' => $openingStock,
            'closingStock' => $closingStock,
            'costOfSales' => $costOfSales,
            'directExpensesTotal' => $directExpensesTotal,
            'salesTotal' => $salesTotal,
            'grossResult' => $grossResult,
            'purchaseTotal' => $purchaseTotal,
            'grossType' => $grossResult >= 0 ? 'profit' : 'loss',
            'overall_total_debit' => $overallDebit,
            'overall_total_credit' => $overallCredit,
            'grossCredit' => $grossCredit,
            'grossDebit' => $grossDebit,
            'showOpening' => $showOpening,
            'showClosing' => $showClosing,
            'showTransactions' => $showTransactions,
            'nettTransactions' => $nettTransactions,
            'detailedView' => $detailedView,
            'group_id' => $request->input('group_id')
        ];
    }

    public function get_loss_profit_data($request)
    {
        $TradingAccount = $this->calculateTradingAccountData($request);

        $request['group_id'] = 11;
        $indirect_incomes = $this->calculateGetTrialBalanceData($request);

        $request['group_id'] = 10;
        $indirect_expenses = $this->calculateGetTrialBalanceData($request);

        // حساب صافي الربح/الخسارة
        $grossProfit = $TradingAccount['grossResult'];
        $totalIndirectIncomes = $indirect_incomes['overall_total_closing'];
        $totalIndirectExpenses = $indirect_expenses['overall_total_closing'];

        $netProfitBeforeExpenses = $grossProfit + abs($totalIndirectIncomes);
        $netProfitLoss = $netProfitBeforeExpenses - $totalIndirectExpenses;

        return [
            'TradingAccount'=> $TradingAccount,
            'indirect_incomes'=> $indirect_incomes,
            'indirect_expenses'=> $indirect_expenses,
            'netProfitLoss'=> $netProfitLoss,
        ];
    }


} //end of class
