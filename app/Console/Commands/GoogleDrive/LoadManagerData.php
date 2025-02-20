<?php

namespace App\Console\Commands\GoogleDrive;

use App\CRM\Commands\GetAllData;
use App\Models\Manager;
use App\Services\CityService;
use App\Services\ExcelService;
use App\Services\ManagersService;
use Illuminate\Console\Command;

class LoadManagerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-manager-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $excelService = ExcelService::getFromApp();
        $cityService = CityService::getFromApp();
        $managerService = ManagersService::getFromApp();
        $cities = $cityService->possibleCityCodes;

        foreach ($cities as $city) {
            $allNames = [];
            $managerPhones = $excelService->getManagerPhonePairs($city);

            foreach ($managerPhones as $managerName => $phone) {
                $allNames[] = $managerName;
                $manager = Manager::withTrashed()->where('phone', $phone)->where('city', $city)->first();
                $data = ['phone' => $phone, 'document_name' => $managerName, 'city' => $city];

                if ($manager == null) {
                    $manager = Manager::create($data);
                } else {
                    if ($manager->trashed()) {
                        $manager->restore();
                    }

                    $manager->update($data);
                }
            }

            //https://docs.google.com/document/d/1TLXZxy2PR1_MZwpKROGV_TMTRbFEltrMOTtWWFyK_WA/edit#heading=h.ktuvt7okpnsd
            $getDataCommand = new GetAllData($city);
            $allData = json_decode($getDataCommand->execute());
            $users = $allData->crm->users;

            foreach ($users as $user) {
                $id = $user->id;
                $fullname = "{$user->name} {$user->surname}";
                $manager = Manager::where('document_name', $fullname)->where('city', $city)->first();

                if ($manager == null) {
                    continue;
                } else {
                    $manager->update(['crm_id' => $id]);
                }
            }
            continue;
            //Надо пофиксить удаление
            $managersNotInDocument = Manager::whereNotIn('document_name', $allNames)->get();

            foreach ($managersNotInDocument as $manager) {
                $managerService->deleteManager($manager);
            }
        }
    }
}
