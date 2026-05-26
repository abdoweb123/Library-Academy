<?php

namespace App\Http\Services\Dashboard\Reports;

use App\Http\Services\MainService;
use App\Models\Group;
use Illuminate\Http\Request;

trait TrailBalanceService
{
    use MainService;

//    public function calculateGetTrialBalanceData(Request $request)
//    {
//        // لو فيه أي inputs جايه من الفورم
//        if ($request->hasAny(['show_opening', 'show_closing', 'show_transactions', 'nett_transactions', 'detailed_view'])) {
//            $showOpening = filter_var($request->input('show_opening', false), FILTER_VALIDATE_BOOLEAN);
//            $showClosing = filter_var($request->input('show_closing', false), FILTER_VALIDATE_BOOLEAN);
//            $showTransactions = filter_var($request->input('show_transactions', false), FILTER_VALIDATE_BOOLEAN);
//            $nettTransactions = filter_var($request->input('nett_transactions', false), FILTER_VALIDATE_BOOLEAN);
//            $detailedView = filter_var($request->input('detailed_view', false), FILTER_VALIDATE_BOOLEAN);
//        } else {
//            // default values (لأول مرة)
//            $showOpening = false;
//            $showClosing = true;
//            $showTransactions = true;
//            $nettTransactions = false;
//            $detailedView = false;
//        }
//
//        $filterStartDate = setting('filterStartDate');
//        $filterEndDate = setting('filterEndDate');
//
//        // لو فيه group_id جاي من request
//        $groupId = $request->input('group_id');
//
//        $groupsQuery = Group::with([
//            'ledgers' => function ($query) use ($filterStartDate, $filterEndDate) {
//                $query->select('id', 'uuid', 'title', 'group_id', 'opening_balance', 'balance_type')
//                    ->with(['voucherTransactions' => function ($q) use ($filterStartDate, $filterEndDate) {
//                        $q->whereNotNull('voucher_id')
//                            ->whereHas('voucher', function ($v) use ($filterStartDate, $filterEndDate) {
//                                if ($filterStartDate && $filterEndDate) {
//                                    $v->whereBetween('date', [$filterStartDate, $filterEndDate]);
//                                }
//                            });
//                    }]);
//            },
//            'children.ledgers' => function ($query) use ($filterStartDate, $filterEndDate) {
//                $query->with(['voucherTransactions' => function ($q) use ($filterStartDate, $filterEndDate) {
//                    $q->whereNotNull('voucher_id')
//                        ->whereHas('voucher', function ($v) use ($filterStartDate, $filterEndDate) {
//                            if ($filterStartDate && $filterEndDate) {
//                                $v->whereBetween('date', [$filterStartDate, $filterEndDate]);
//                            }
//                        });
//                }]);
//            }
//        ]);
//
//        if ($groupId) {
//            $groupsQuery->where('id', $groupId);
//        } else {
//            $mainGroups = Group::query()->whereNull('group_id')->pluck('id')->toArray();
//            $groupsQuery->whereIn('group_id', $mainGroups);
//        }
//
//        $groups = $groupsQuery->get();
//
//        // 🔹 إجماليات عامة
//        $overallOpening = 0;
//        $overallDebit = 0;
//        $overallCredit = 0;
//        $overallClosing = 0;
//
//        // 🔹 إضافات جديدة
//        $total_debit_opening = 0;
//        $total_credit_opening = 0;
//        $total_debit_closing = 0;
//        $total_credit_closing = 0;
//
//        $result = [];
//
//        foreach ($groups as $group) {
//            $groupOpening = 0;
//            $groupDebit = 0;
//            $groupCredit = 0;
//            $groupClosing = 0;
//
//            $rows = [];
//
//            // ✅ 1. ledgers المباشرة
//            foreach ($group->ledgers as $ledger) {
//                $opening = $ledger->balance_type === 'dr' ? $ledger->opening_balance : -$ledger->opening_balance;
//
//                $debit = $ledger->voucherTransactions->sum('debit');
//                $credit = $ledger->voucherTransactions->sum('credit');
//
//                if ($nettTransactions) {
//                    $net = $debit - $credit;
//                    $debit = $net > 0 ? $net : 0;
//                    $credit = $net < 0 ? abs($net) : 0;
//                }
//
//                $closing = $opening + ($debit - $credit);
//
//                if (!($debit == 0 && $credit == 0)) {
//                    $rows[] = [
//                        'particular_id' => $ledger->uuid,
//                        'particular_name' => $ledger->title,
//                        'opening_balance' => $showOpening ? abs($ledger->opening_balance) : null,
//                        'opening_type' => $showOpening ? ucfirst($ledger->balance_type) : null,
//                        'debit' => $showTransactions ? $debit : null,
//                        'credit' => $showTransactions ? $credit : null,
//                        'closing_balance' => abs($closing),
//                        'closing_type' => $closing >= 0 ? 'Dr' : 'Cr',
//                        'type' => 'ledger'
//                    ];
//                }
//
//                $groupOpening += $opening;
//                $groupDebit += $debit;
//                $groupCredit += $credit;
//                $groupClosing += $closing;
//            }
//
//            // ✅ 2. subgroups
//            foreach ($group->children as $child) {
//                $childOpening = 0;
//                $childDebit = 0;
//                $childCredit = 0;
//
//                foreach ($child->ledgers as $ledger) {
//                    $opening = $ledger->balance_type === 'dr' ? $ledger->opening_balance : -$ledger->opening_balance;
//                    $debit = $ledger->voucherTransactions->sum('debit');
//                    $credit = $ledger->voucherTransactions->sum('credit');
//
//                    if ($nettTransactions) {
//                        $net = $debit - $credit;
//                        $debit = $net > 0 ? $net : 0;
//                        $credit = $net < 0 ? abs($net) : 0;
//                    }
//
//                    $childOpening += $opening;
//                    $childDebit += $debit;
//                    $childCredit += $credit;
//                }
//
//                $childClosing = $childOpening + ($childDebit - $childCredit);
//
//                if (!($childDebit == 0 && $childCredit == 0)) {
//                    $rows[] = [
//                        'particular_id' => $child->id,
//                        'particular_name' => $child->title,
//                        'opening_balance' => $showOpening ? abs($childOpening) : null,
//                        'opening_type' => $showOpening ? ($childOpening >= 0 ? 'Dr' : 'Cr') : null,
//                        'debit' => $showTransactions ? $childDebit : null,
//                        'credit' => $showTransactions ? $childCredit : null,
//                        'closing_balance' => abs($childClosing),
//                        'closing_type' => $childClosing >= 0 ? 'Dr' : 'Cr',
//                        'type' => 'group'
//                    ];
//                }
//
//                $groupOpening += $childOpening;
//                $groupDebit += $childDebit;
//                $groupCredit += $childCredit;
//                $groupClosing += $childClosing;
//            }
//
//            if (!empty($rows) && abs($groupClosing) != 0) {
//                $result[] = [
//                    'group_id' => $group->id,
//                    'group_name' => $group->title,
//                    'rows' => $detailedView ? $rows : [],
//                    'totals' => [
//                        'opening_balance' => $showOpening ? abs($groupOpening) : null,
//                        'opening_type' => $showOpening ? ($groupOpening >= 0 ? 'Dr' : 'Cr') : null,
//                        'total_debit' => $showTransactions ? $groupDebit : null,
//                        'total_credit' => $showTransactions ? $groupCredit : null,
//                        'closing_balance' => abs($groupClosing),
//                        'closing_type' => $showClosing ? ($groupClosing >= 0 ? 'Dr' : 'Cr') : '',
//                    ]
//                ];
//
//                // جمع المجاميع
//                $overallOpening += $groupOpening;
//                $overallDebit += $groupDebit;
//                $overallCredit += $groupCredit;
//                $overallClosing += $groupClosing;
//
//                // 🔹 تحديث الإجماليات التفصيلية
//                if ($groupOpening >= 0) {
//                    $total_debit_opening += abs($groupOpening);
//                } else {
//                    $total_credit_opening += abs($groupOpening);
//                }
//
//                if ($groupClosing >= 0) {
//                    $total_debit_closing += abs($groupClosing);
//                } else {
//                    $total_credit_closing += abs($groupClosing);
//                }
//            }
//        }
//
//        return [
//            'groups' => $result,
//            'overall_total_opening' => $overallOpening,
//            'overall_total_debit' => $overallDebit,
//            'overall_total_credit' => $overallCredit,
//            'overall_total_closing' => $overallClosing,
//
//            // ✅ الإضافات الجديدة
//            'total_debit_opening' => $total_debit_opening,
//            'total_credit_opening' => $total_credit_opening,
//            'total_debit_closing' => $total_debit_closing,
//            'total_credit_closing' => $total_credit_closing,
//
//            'showOpening' => $showOpening,
//            'showClosing' => $showClosing,
//            'showTransactions' => $showTransactions,
//            'nettTransactions' => $nettTransactions,
//            'detailedView' => $detailedView,
//            'group_id' => $request->input('group_id')
//        ];
//    }
    public function calculateGetTrialBalanceData(Request $request)
    {
        $mainGroups = Group::query()->whereNull('group_id')->pluck('id')->toArray();

        $calculatedData = $this->calculateGroupTotalsWithChildrenLedgersAndSubGroups($request, $mainGroups);

        return [
            'groups' => $calculatedData['groups'],
            'overall_total_opening' => $calculatedData['overall_total_opening'],
            'overall_total_debit' => $calculatedData['overall_total_debit'],
            'overall_total_credit' => $calculatedData['overall_total_credit'],
            'overall_total_closing' => $calculatedData['overall_total_closing'],

            // ✅ الإضافات الجديدة
            'total_debit_opening' => $calculatedData['total_debit_opening'],
            'total_credit_opening' => $calculatedData['total_credit_opening'],
            'total_debit_closing' => $calculatedData['total_debit_closing'],
            'total_credit_closing' => $calculatedData['total_credit_closing'],

            'showOpening' => $calculatedData['showOpening'],
            'showClosing' => $calculatedData['showClosing'],
            'showTransactions' => $calculatedData['showTransactions'],
            'nettTransactions' => $calculatedData['nettTransactions'],
            'detailedView' => $calculatedData['detailedView'],
            'group_id' => $calculatedData['group_id']
        ];
    }


    public function calculateGroupTotalsWithChildrenLedgersAndSubGroups($request, $mainGroups_ids=[], $groups_ids=[], $getEmptyGroups=false)
    {
//        return $groups_ids;
        // لو فيه أي inputs جايه من الفورم
        if ($request->hasAny(['show_opening', 'show_closing', 'show_transactions', 'nett_transactions', 'detailed_view'])) {
            $showOpening = filter_var($request->input('show_opening', false), FILTER_VALIDATE_BOOLEAN);
            $showClosing = filter_var($request->input('show_closing', false), FILTER_VALIDATE_BOOLEAN);
            $showTransactions = filter_var($request->input('show_transactions', false), FILTER_VALIDATE_BOOLEAN);
            $nettTransactions = filter_var($request->input('nett_transactions', false), FILTER_VALIDATE_BOOLEAN);
            $detailedView = filter_var($request->input('detailed_view', false), FILTER_VALIDATE_BOOLEAN);
        } else {
            // default values (لأول مرة)
            $showOpening = false;
            $showClosing = true;
            $showTransactions = true;
            $nettTransactions = false;
            $detailedView = false;
        }

        $filterStartDate = setting('filterStartDate');
        $filterEndDate = setting('filterEndDate');

        // لو فيه group_id جاي من request
        $groupId = $request->input('group_id');

        $groupsQuery = Group::with([
            'ledgers' => function ($query) use ($filterStartDate, $filterEndDate) {
                $query->select('id', 'uuid', 'title', 'group_id', 'opening_balance', 'balance_type')
                    ->with(['voucherTransactions' => function ($q) use ($filterStartDate, $filterEndDate) {
                        $q->whereNotNull('voucher_id')
                            ->whereHas('voucher', function ($v) use ($filterStartDate, $filterEndDate) {
                                if ($filterStartDate && $filterEndDate) {
                                    $v->whereBetween('date', [$filterStartDate, $filterEndDate]);
                                }
                            });
                    }]);
            },
            'children.ledgers' => function ($query) use ($filterStartDate, $filterEndDate) {
                $query->with(['voucherTransactions' => function ($q) use ($filterStartDate, $filterEndDate) {
                    $q->whereNotNull('voucher_id')
                        ->whereHas('voucher', function ($v) use ($filterStartDate, $filterEndDate) {
                            if ($filterStartDate && $filterEndDate) {
                                $v->whereBetween('date', [$filterStartDate, $filterEndDate]);
                            }
                        });
                }]);
            }
        ]);


        if ($groupId) {
            $groupsQuery->where('id', $groupId);
        } else {

            if ($mainGroups_ids) {
                $groupsQuery->whereIn('group_id', $mainGroups_ids);
            }
            elseif ($groups_ids) {
                $groupsQuery->whereIn('id', $groups_ids);
            }
        }

        $groups = $groupsQuery->get();

        // 🔹 إجماليات عامة
        $overallOpening = 0;
        $overallDebit = 0;
        $overallCredit = 0;
        $overallClosing = 0;

        // 🔹 إضافات جديدة
        $total_debit_opening = 0;
        $total_credit_opening = 0;
        $total_debit_closing = 0;
        $total_credit_closing = 0;

        $result = [];

        foreach ($groups as $group) {
            $groupOpening = 0;
            $groupDebit = 0;
            $groupCredit = 0;
            $groupClosing = 0;

            $rows = [];

            // ✅ 1. ledgers المباشرة
            foreach ($group->ledgers as $ledger) {
                $opening = $ledger->balance_type === 'dr' ? $ledger->opening_balance : -$ledger->opening_balance;

                $debit = $ledger->voucherTransactions->sum('debit');
                $credit = $ledger->voucherTransactions->sum('credit');

                if ($nettTransactions) {
                    $net = $debit - $credit;
                    $debit = $net > 0 ? $net : 0;
                    $credit = $net < 0 ? abs($net) : 0;
                }

                $closing = $opening + ($debit - $credit);

                // To get ledgers even if only have transactions
                if (!($debit == 0 && $credit == 0 && $opening == 0)) {
                    $rows[] = [
                        'particular_id' => $ledger->uuid,
                        'particular_name' => $ledger->title,
                        'opening_balance' => $showOpening ? abs($ledger->opening_balance) : null,
                        'opening_type' => $showOpening ? ucfirst($ledger->balance_type) : null,
                        'debit' => $showTransactions ? $debit : null,
                        'credit' => $showTransactions ? $credit : null,
                        'closing_balance' => abs($closing),
                        'closing_type' => $closing >= 0 ? 'Dr' : 'Cr',
                        'type' => 'ledger'
                    ];
                }

                $groupOpening += $opening;
                $groupDebit += $debit;
                $groupCredit += $credit;
                $groupClosing += $closing;
            }

            // ✅ 2. subgroups
            foreach ($group->children as $child) {
                $childOpening = 0;
                $childDebit = 0;
                $childCredit = 0;

                foreach ($child->ledgers as $ledger) {
                    $opening = $ledger->balance_type === 'dr' ? $ledger->opening_balance : -$ledger->opening_balance;
                    $debit = $ledger->voucherTransactions->sum('debit');
                    $credit = $ledger->voucherTransactions->sum('credit');

                    if ($nettTransactions) {
                        $net = $debit - $credit;
                        $debit = $net > 0 ? $net : 0;
                        $credit = $net < 0 ? abs($net) : 0;
                    }

                    $childOpening += $opening;
                    $childDebit += $debit;
                    $childCredit += $credit;
                }

                $childClosing = $childOpening + ($childDebit - $childCredit);

                // To get sub-groups even if only have transactions
                if ( !($childDebit == 0 && $childCredit == 0 && $childOpening == 0) ) {
                    $rows[] = [
                        'particular_id' => $child->id,
                        'particular_name' => $child->title,
                        'opening_balance' => $showOpening ? abs($childOpening) : null,
                        'opening_type' => $showOpening ? ($childOpening >= 0 ? 'Dr' : 'Cr') : null,
                        'debit' => $showTransactions ? $childDebit : null,
                        'credit' => $showTransactions ? $childCredit : null,
                        'closing_balance' => abs($childClosing),
                        'closing_type' => $childClosing >= 0 ? 'Dr' : 'Cr',
                        'type' => 'group'
                    ];
                }

                $groupOpening += $childOpening;
                $groupDebit += $childDebit;
                $groupCredit += $childCredit;
                $groupClosing += $childClosing;
            }


            if ($getEmptyGroups || !empty($rows) && abs($groupClosing) != 0) {
                $result[] = [
                    'group_id' => $group->id,
                    'group_name' => $group->title,
                    'rows' => $detailedView ? $rows : [],
                    'totals' => [
                        'opening_balance' => $showOpening ? abs($groupOpening) : null,
                        'opening_type' => $showOpening ? ($groupOpening >= 0 ? 'Dr' : 'Cr') : null,
                        'total_debit' => $showTransactions ? $groupDebit : null,
                        'total_credit' => $showTransactions ? $groupCredit : null,
                        'closing_balance' => abs($groupClosing),
                        'closing_type' => $showClosing ? ($groupClosing >= 0 ? 'Dr' : 'Cr') : '',
                    ]
                ];

                // جمع المجاميع
                $overallOpening += $groupOpening;
                $overallDebit += $groupDebit;
                $overallCredit += $groupCredit;
                $overallClosing += $groupClosing;

                // 🔹 تحديث الإجماليات التفصيلية
                if ($groupOpening >= 0) {
                    $total_debit_opening += abs($groupOpening);
                } else {
                    $total_credit_opening += abs($groupOpening);
                }

                if ($groupClosing >= 0) {
                    $total_debit_closing += abs($groupClosing);
                } else {
                    $total_credit_closing += abs($groupClosing);
                }
            }
        }

        return [
            'groups' => $result,
            'overall_total_opening' => $overallOpening,
            'overall_total_debit' => $overallDebit,
            'overall_total_credit' => $overallCredit,
            'overall_total_closing' => $overallClosing,

            // ✅ الإضافات الجديدة
            'total_debit_opening' => $total_debit_opening,
            'total_credit_opening' => $total_credit_opening,
            'total_debit_closing' => $total_debit_closing,
            'total_credit_closing' => $total_credit_closing,

            'showOpening' => $showOpening,
            'showClosing' => $showClosing,
            'showTransactions' => $showTransactions,
            'nettTransactions' => $nettTransactions,
            'detailedView' => $detailedView,
            'group_id' => $request->input('group_id')
        ];
    }


} //end of class
