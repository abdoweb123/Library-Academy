<?php

namespace App\Http\Services;

use App\Models\Setting;

trait MainService
{
    use LedgerMainService;

    // Calculate Group Total (filter by main date)
    function calculateGroupTotals($group)
    {
        $totalCredit = 0;
        $totalDebit = 0;

        // Eager load children and transactions to ensure relationships are loaded
        $group->load('children', 'transactions.ledgers');

        // Check if the groups has children
        if ($group->children->count() > 0) {
            // Recursively calculate totals for child groups
            foreach ($group->children as $child) {
                $childTotals = $this->calculateGroupTotals($child);
                // Add child totals to current groups totals
                $totalCredit += $childTotals['totalCredit'];
                $totalDebit += $childTotals['totalDebit'];
            }
        } else {
            // Group has no children, calculate totals for transactions directly
            if ($group->transactions->count() > 0) { // Check if transactions exist

                $transactionsQuery = $group->transactions();

                // Apply date filter to transactions if filter dates are set
                $transactionsQuery = $this->applyDateFilter($transactionsQuery);

                $transactions = $transactionsQuery->get();

                foreach ($transactions as $transaction) {
                    $totalCredit += $transaction->ledgers()->where('type', 'credit')->sum('amount');
                    $totalDebit += $transaction->ledgers()->where('type', 'debit')->sum('amount');
                }

            }
        }

        // Calculate the difference (total credit - total debit) for the groups
        $groupTotal = $totalCredit - $totalDebit;

        return [
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'groupTotal' => $groupTotal,
        ];
    }


    // Filter by date ( main date in setting )
    function applyDateFilter($query)
    {
        $filterStartDate = setting('filterStartDate');
        $filterEndDate = setting('filterEndDate');

        if ($filterStartDate) {
            $query->where('date', '>=', $filterStartDate);
        }
        if ($filterEndDate) {
            $query->where('date', '<=', $filterEndDate);
        }


        return $query;
    }


    /*** save search in setting ***/
    public function saveSearchSetting($request)
    {
        // Apply date filters if present
        $dateStart = $request->get('date_start');
        $dateEnd = $request->get('date_end');

        // Save date filters to settings
        Setting::updateOrCreate(['key' => 'filterStartDate'], ['value' => $dateStart]);
        Setting::updateOrCreate(['key' => 'filterEndDate'], ['value' => $dateEnd]);
    }


    /*** inputs for (filters) ***/
    public function filterInputs()
    {
        $inputs = [
            [
                'title' => trns('date'),
                'inputs' => [
                    [
                        'id' => 'date_start',
                        'type' => 'datetime-local',
                        'label' => trns('start_date'),
                        'class' => 'datepicker mb-2',
                        'value' => setting('filterStartDate'),
                    ],
                    [
                        'id' => 'date_end',
                        'type' => 'datetime-local',
                        'label' => trns('end_date'),
                        'class' => 'datepicker mb-2',
                        'value' => setting('filterEndDate'),
                    ],
                ],
            ],
        ];

        return $inputs;
    }


} //end of class
