<?php

namespace App\Console\Commands\TrendAgent;

use App\Models\Apartment;
use App\Models\ResidentialComplex;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LinkApartmentsToComplexesCommand extends Command
{
    protected $signature = 'trend-agent:link-apartments {--limit=1000} {--dry-run}';
    protected $description = 'Link apartments to complexes using block_id and other keys';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $dryRun = $this->option('dry-run');
        
        $this->info("Starting apartment linking process...");
        $this->info("Limit: {$limit} apartments");
        $this->info("Dry run: " . ($dryRun ? 'Yes' : 'No'));
        
        $apartmentsWithoutComplex = Apartment::whereNull('complex_key')
            ->limit($limit)
            ->get();
            
        $this->info("Found {$apartmentsWithoutComplex->count()} apartments without complex_key");
        
        $linkedCount = 0;
        $updatedCount = 0;
        
        foreach ($apartmentsWithoutComplex as $apartment) {
            $complexKey = $this->findComplexKeyForApartment($apartment);
            
            if ($complexKey) {
                if (!$dryRun) {
                    $apartment->update(['complex_key' => $complexKey]);
                    $this->updateComplexFromApartment($apartment, $complexKey);
                }
                $linkedCount++;
                
                if ($linkedCount % 100 === 0) {
                    $this->info("Processed {$linkedCount} apartments...");
                }
            }
        }
        
        $this->info("Completed!");
        $this->info("Linked apartments: {$linkedCount}");
        
        return 0;
    }
    
    private function findComplexKeyForApartment(Apartment $apartment): ?string
    {
        $meta = json_decode($apartment->meta, true);
        $originalData = $meta['original_data'] ?? [];
        
        $possibleKeys = [
            'block_id',
            'complex_id', 
            'complex',
            'block',
            'building_id'
        ];

        foreach ($possibleKeys as $key) {
            if (isset($originalData[$key]) && !empty($originalData[$key])) {
                $complexKey = $originalData[$key];
                
                $complex = ResidentialComplex::where('key', $complexKey)->first();
                if ($complex) {
                    return $complexKey;
                }
            }
        }

        $apartmentId = $originalData['_id'] ?? null;
        if ($apartmentId) {
            $complex = ResidentialComplex::where('key', 'LIKE', '%' . substr($apartmentId, 0, 8) . '%')->first();
            if ($complex) {
                return $complex->key;
            }
        }

        return null;
    }
    
    private function updateComplexFromApartment(Apartment $apartment, string $complexKey): void
    {
        $meta = json_decode($apartment->meta, true);
        $originalData = $meta['original_data'] ?? [];
        
        $complex = ResidentialComplex::where('key', $complexKey)->first();
        if (!$complex) {
            return;
        }
        
        $updated = false;
        
        $blockBuilderName = $originalData['block_builder_name'] ?? null;
        if ($blockBuilderName && $complex->builder !== $blockBuilderName) {
            $complex->builder = $blockBuilderName;
            $updated = true;
        }
        
        $blockSubwayName = $originalData['block_subway_name'] ?? null;
        if ($blockSubwayName && is_array($blockSubwayName) && count($blockSubwayName) > 0) {
            $metroName = $blockSubwayName[0];
            if ($metroName && $complex->metro_station !== $metroName) {
                $complex->metro_station = $metroName;
                $updated = true;
            }
        }
        
        $blockAddress = $originalData['block_address'] ?? null;
        if ($blockAddress && $complex->address !== $blockAddress) {
            $complex->address = $blockAddress;
            $updated = true;
        }
        
        if ($updated) {
            $complex->save();
        }
    }
}
