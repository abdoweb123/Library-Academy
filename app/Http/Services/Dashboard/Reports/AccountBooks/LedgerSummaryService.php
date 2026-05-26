<?php

namespace App\Http\Services\Dashboard\Reports\AccountBooks;

use App\Http\Services\MainService;
use App\Models\VoucherTransaction;

trait LedgerSummaryService
{
    use MainService;

    // To get the opening balance before the current date
    public function previousLedgerSiblingsAmount($ledger, $voucherIds, $filterStartDate, $filterEndDate)
    {
//        return $ledger;
        $previousLedgerSiblingsAmount = VoucherTransaction::whereIn('voucher_id', $voucherIds)
            ->whereNot(function ($q) use ($ledger) {
                $q->where('ledger_uuid', $ledger->uuid);
            })
            // Here we get all transactions before the filter start date
            ->whereHas('voucher', function ($query) use ($filterStartDate, $filterEndDate) {
                if ($filterStartDate) {
                    $query->where('date' ,'<', $filterStartDate);
                }
            })
            ->get(); // Fetch all transactions first

        // Calculate total debit and credit
        // To (debit is credit) and (credit is debit)
        $totalDebit = (float) $previousLedgerSiblingsAmount->sum('credit');
        $totalCredit = (float) $previousLedgerSiblingsAmount->sum('debit');

        // Get opening balance and balance type
        $openingBalance = (float) $ledger->opening_balance;
        $balanceType = $ledger->balance_type; // Assume 'cr' or 'dr'

        // Adjust opening balance based on type
        if ($balanceType === 'dr') {
            $totalDebit += $openingBalance;
        } else if($balanceType === 'cr') {
            $totalCredit += $openingBalance;
        }


        // Calculate the difference and determine type
        if ($totalDebit > $totalCredit) {
            $result = $totalDebit - $totalCredit;
            $type = 'dr';
        } else {
            $result = $totalCredit - $totalDebit;
            $type = 'cr';
        }

        return [
            'openingBalance' => $result,
            'type' => $type,
        ];
    }


    // Retrieve all voucher transactions that share any of the voucher_ids
    public function ledgerSiblings($voucherIds, $ledger, $filterStartDate, $filterEndDate)
    {
        $ledgerSiblings = VoucherTransaction::whereIn('voucher_id', $voucherIds)
            ->whereNot(function ($q) use ($ledger) {
                $q->where('ledger_uuid', $ledger->uuid);
            })
            ->whereHas('voucher', function($query) use ($filterStartDate, $filterEndDate) {
                if ($filterStartDate && $filterEndDate) {
                    $query->whereBetween('date', [$filterStartDate, $filterEndDate]); // Apply date filter from Voucher
                }
            })
            ->get()
            ->groupBy('voucher_id') // Group by voucher_id
            ->map(function ($transactions) {
                $total_debit = $transactions->sum('debit');
                $total_credit = $transactions->sum('credit');
                $details = $transactions->map(function ($transaction) {
                    return [
                        'transaction_id' => $transaction->id, // Format date
//                        'created_at' => $transaction->created_at->format('j-M-y'), // Format date
                        'created_at' => \Carbon\Carbon::parse($transaction->voucher->date)->format('d-M-y'), // Format date
                        'ledger_title' => $transaction->ledger->title ?? 'N/A',
                        'ref_number' => $transaction->voucher->ref_number ?? '',
                        'voucher_id' => $transaction->voucher_id,
                        'debit' => $transaction->debit,
                        'credit' => $transaction->credit,
                    ];
                });

                return [
                    'voucher_id' => $transactions->first()->voucher_id,
                    'total_debit' => $total_debit,
                    'total_credit' => $total_credit,
                    'details' => $details,
                    'date' => $details->first()['created_at'], // Store date for summary row
                ];
            })
            ->values(); // Reset the array keys

        return $ledgerSiblings;
    }

//    public function ledgerSiblings($voucherIds, $ledger, $filterStartDate, $filterEndDate)
//    {
//        $ledgerSiblings = VoucherTransaction::whereIn('voucher_id', $voucherIds)
//            ->whereHas('voucher', function($query) use ($filterStartDate, $filterEndDate) {
//                if ($filterStartDate && $filterEndDate) {
//                    $query->whereBetween('date', [$filterStartDate, $filterEndDate]); // Apply date filter from Voucher
//                }
//            })
//            ->get()
//            ->groupBy('voucher_id')
//            ->map(function ($transactions) {
//                $details = $transactions->map(function ($transaction) {
//                    return [
//                        'transaction_id' => $transaction->id,
//                        'created_at' => \Carbon\Carbon::parse($transaction->voucher->date)->format('d-M-y'),
//                        'ledger_title' => $transaction->ledger->title ?? 'N/A',
//                        'ref_number' => $transaction->voucher->ref_number ?? '',
//                        'voucher_id' => $transaction->voucher_id,
//                        'debit' => $transaction->debit,
//                        'credit' => $transaction->credit,
//                    ];
//                });
//
//                // 🔹 شيل نسخة واحدة فقط لو فيه ledger_title مكرر و debit > 0
//                $removed = [];
//                $details = $details->filter(function ($item) use (&$removed) {
//                    if ($item['debit'] > 0) {
//                        if (!in_array($item['ledger_title'], $removed)) {
//                            $removed[] = $item['ledger_title']; // احذف نسخة واحدة بس
//                            return false;
//                        }
//                    }
//                    return true;
//                })->values();
//
//                $total_debit = $details->sum('debit');
//                $total_credit = $details->sum('credit');
//
//                return [
//                    'voucher_id' => $transactions->first()->voucher_id,
//                    'total_debit' => $total_debit,
//                    'total_credit' => $total_credit,
//                    'details' => $details,
//                    'date' => $details->first()['created_at'] ?? null,
//                ];
//            })
//            ->values();
//
//        return $ledgerSiblings;
//    }

    public function calculateLedgerBalances($ledgerSiblings, $openingBalance, $balanceType)
    {
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($ledgerSiblings as $group) {
//            $totalCredit += $group['total_credit'];
//            $totalDebit += $group['total_debit'];

            // Here we get the of  balance type
            $totalDebit += $group['total_credit'];
            $totalCredit += $group['total_debit'];
        }

        $openingDebit = $balanceType === 'dr' ? $openingBalance : 0;
        $openingCredit = $balanceType === 'cr' ? $openingBalance : 0;

        $closingDebit = $openingDebit + $totalDebit;
        $closingCredit = $openingCredit + $totalCredit;

        if ($closingDebit > $closingCredit) {
            $closingDebit = $closingDebit - $closingCredit;
            $closingCredit = 0;
        } elseif ($closingCredit > $closingDebit) {
            $closingCredit = $closingCredit - $closingDebit;
            $closingDebit = 0;
        } else {
            $closingDebit = $closingCredit = 0;
        }

        return [
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'openingDebit' => $openingDebit,
            'openingCredit' => $openingCredit,
            'closingDebit' => $closingDebit,
            'closingCredit' => $closingCredit,
        ];
    }

    public function processLedgerGroups($ledgerSiblings): array
    {
        $processedGroups = [];

        foreach ($ledgerSiblings as $group) {

//            $total = $group['total_debit'] - $group['total_credit'];
//
//            $debitValue = $total < 0 ? abs($total) : 0;
//            $creditValue = $total >= 0 ? $total : 0;

//            if (count($group['details']) > 1) {
//                $total = $group['total_debit'] - $group['total_credit'];
//
//                $creditValue = $total < 0 ? abs($total) : 0;
//                $debitValue = $total >= 0 ? $total : 0;
//            }
//            else{
//                $debitValue = $group['total_debit'];
//                $creditValue = $group['total_credit'];
//            }

            $debitValue = $group['total_debit'];
            $creditValue = $group['total_credit'];

            $processedGroups[] = [
                'voucher_id' => $group['voucher_id'],
                'ref_number' => $group['details'][0]['ref_number'] ?? '-',
                'date' => $group['date'],
                'particulars' => count($group['details']) > 1
                    ? '(as per details)'
                    : ($group['details'][0]['ledger_title'] ?? ''),
                'debit' => $debitValue,
                'credit' => $creditValue,
                'details' => $group['details'],
                'has_multiple' => count($group['details']) > 1,
            ];
        }

        return $processedGroups;
    }

    public function openingBalanceTransaction($ledger)
    {
        $openingBalanceTransaction = VoucherTransaction::query()
            ->whereNull('voucher_id')
            ->where('ledger_uuid', $ledger->uuid)
            ->first();

        return $openingBalanceTransaction;
    }

    public function prepareLedgerDisplayData($ledger, $ledgerSiblings, $openingBalance, $balanceType)
    {
        $balances = $this->calculateLedgerBalances($ledgerSiblings, $openingBalance, $balanceType);
        $openingBalanceTransaction = $this->openingBalanceTransaction($ledger);
        $processedGroups = $this->processLedgerGroups($ledgerSiblings);

        return array_merge($balances, [
            'processedGroups' => $processedGroups,
            'ledger' => $ledger,
//            'ledger_type' => $this->getTypeFromModelClass($ledger->getMorphClass()),
            'openingBalance' => $openingBalance,
            'balanceType' => $balanceType,
            'openingBalanceTransaction' => $openingBalanceTransaction,
        ]);
    }



} //end of class
