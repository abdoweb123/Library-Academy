<?php

namespace App\Http\Services;


use App\Models\Country;
use App\Models\Ledger;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;

trait LedgerMainService
{
    public function getModelLedger($ledgerId)
    {
        $ledger = Ledger::query()->where('uuid', $ledgerId)->first();
        return $ledger;
    }


    /**
     * Get all ledgers, including external ones if applicable.
     * true when get them in create voucher page
     * false for reports page
     **/
    public function getAllLedgers($getExternal = true)
    {
        $search = request()->term;

        $ledgers = Ledger::select('id', 'uuid', 'title');

        if (!empty($search)) {
            $ledgers->where('title', 'like', '%' . $search . '%');
        }

        //convert query Collection
        $ledgers = $ledgers->get();

        // only if original_app = 0 will get External Ledgers
        $externalLedgers = collect();
        if (setting('original_app') == 0 && $getExternal) {
            $externalLedgers = $this->getExternalLedgers($search) ?? collect();
        }

        // merge results + make unique by uuid because external ledger can be stored in DB with same uuid
        $results = $ledgers->concat($externalLedgers)
            ->unique('uuid')
            ->values(); // Reorder the index after filtering

        return $results;
    }


    // To get all External ledgers
    public function getExternalLedgers($search = null)
    {
        $source = setting('source_app_url'); // e.g., http://sdm.test

        if (setting('original_app') == 0) {
            try {
//                $response = Http::get($source . '/api/get-all-ledgers');
                $response = Http::get($source . '/api/get-all-ledgers', [
                    'term' => $search,
                ]);

                if ($response->successful()) {
                    $data = collect($response->json());
                    return $data;
                }
            } catch (\Exception $e) {
                // Optionally log the error
            }

            return collect();
        }
        return collect();
    }


    // To get one ledger by its uuid
    protected function fetchExternalLedger($ledger_uuid)
    {
        $source = setting('source_app_url');   // Ex: http://external-system.test

        if (setting('original_app') == 0) {
            try {
                $response = Http::get($source . '/api/get-ledger/' . $ledger_uuid);

                if ($response->successful()) {
                    $data = $response->json();
                    return (object) $data; // return as an object
                }
            } catch (\Exception $e) {
                logger()->error('External ledger fetch failed: ' . $e->getMessage());
            }
        }
        return null;
    }



} //end of class
