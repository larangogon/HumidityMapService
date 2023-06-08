<?php

namespace App\Repositories;

use App\Contracts\ConfigurationContract;
use App\DataObjects\ConfigurationData;
use App\Models\Configuration;
use App\Models\Merchant;
use App\Models\Site;
use Illuminate\Support\Facades\DB;

class ConfigurationRepository implements ConfigurationContract
{
    public function store(ConfigurationData $data): array
    {
        return DB::transaction(function () use ($data) {
            $configuration = (new Configuration())
                ->fillFromConfigurationData($data);

            $configuration->save();

            $document = explode('-', $data->merchant['document'])[0];

            /** @var Merchant $merchant */
            $merchant = Merchant::query()->firstOrCreate(
                ['document' => $document],
                array_replace($data->merchant, ['document' => $document])
            );

            $sites = [];

            foreach ($data->sites as $siteData) {
                $sites[] = Site::firstOrCreate(
                    ['external_id' => $siteData['externalId']],
                    [
                        'external_id' => $siteData['externalId'],
                        'merchant_id' => $merchant->id,
                        'login' => $siteData['login'],
                        'tran_key' => $siteData['tranKey'],
                    ]
                )->toArrayFormat();
            }

            return $configuration->toArrayFormat($merchant, $sites);
        });
    }

    public function update(ConfigurationData $data, Configuration $configuration): Configuration
    {
        $configuration->fillFromConfigurationData($data);
        $configuration->save();

        return $configuration;
    }
}
