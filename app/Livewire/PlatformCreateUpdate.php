<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Models\Item;
use App\Models\TranslaleModel;
use Core\Enum\DealStatus;
use Core\Enum\DealTypeEnum;
use Core\Enum\PlatformType;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PlatformCreateUpdate extends Component
{
    use WithFileUploads;

    public
        $idPlatform,
        $name,
        $description,
        $sector,
        $type,
        $link;
    public $logoImage;
    public $enabled = false;
    public $show_profile = false;
    public $useCoupons = true;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
        'type' => 'required',
        'sector' => 'required',
        'link' => 'required',
        'logoImage' => 'nullable|image|mimes:jpeg,png,jpg',

    ];
    public $update = false;
    public $types = [];
    public $sectors = [];

    public function mount(Request $request)
    {
        $this->idPlatform = $request->query('idPlatform');
        $this->type = PlatformType::Full->value;
        $this->types = [
            ['name' => PlatformType::Full->name, 'value' => PlatformType::Full->value,],
            ['name' => PlatformType::Hybrid->name, 'value' => PlatformType::Hybrid->value,],
            ['name' => PlatformType::Paiement->name, 'value' => PlatformType::Paiement->value,]
        ];
        $sectors = BusinessSector::all();
        foreach ($sectors as $sector) {
            $this->sector = $sector->id;
            $this->sectors[] = ['name' => $sector->name, 'value' => $sector->id];
        }
        if (!is_null($this->idPlatform)) {
            $this->edit($this->idPlatform);
        }
    }

    public function cancel()
    {
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Platform operation cancelled'));
    }

    public function edit($idPlatform)
    {

        $platform = Platform::findOrFail($idPlatform);
        $this->name = $platform?->name;
        $this->description = $platform->description;
        $this->idPlatform = $platform->id;
        $this->sector = $platform->business_sector_id;
        $this->type = $platform->type;
        $this->enabled = $platform->enabled == 1 ? true : false;;
        $this->show_profile = $platform->show_profile == 1 ? true : false;;
        $this->link = $platform->link;
        $this->update = true;
    }

    public function getDealParam($name)
    {
        $param = DB::table('settings')->where("ParameterName", "=", $name)->first();
        if (!is_null($param)) {
            return $param->DecimalValue;
        }
        return 0;
    }

    public function createProduct($platform)
    {
        $initialCommission = 10;

        $deal = $platform->deals()->create([
            'name' => $platform->name . ' Deal',
            'description' => $platform->name . ' Deal',
            'validated' => TRUE,
            'status' => DealStatus::Opened->value,
            'type' => DealTypeEnum::coupons->value,
            'current_turnover' => 0,
            'target_turnover' => 10000,
            'is_turnover' => true,
            'discount' => rand(1, $initialCommission / 2),
            'start_date' => now(),
            'end_date' => now()->addDays(365),
            'initial_commission' => $initialCommission,
            'final_commission' => rand(20, 30),
            'earn_profit' => $this->getDealParam('DEALS_EARN_PROFIT_PERCENTAGE'),
            'jackpot' => $this->getDealParam('DEALS_JACKPOT_PERCENTAGE'),
            'tree_remuneration' => $this->getDealParam('DEALS_TREE_REMUNERATION_PERCENTAGE'),
            'proactive_cashback' => $this->getDealParam('DEALS_PROACTIVE_CASHBACK_PERCENTAGE'),
            'total_commission_value' => 0,
            'total_unused_cashback_value' => 0,
            'platform_id' => $platform->id,
        ]);

        $params = [
            'name' => 'Coupon',
            'ref' => '#0001',
            'price' => 0,
            'discount' => 0,
            'discount_2earn' => 0,
            'deal_id' => $deal->id,
            'platform_id' => $platform->id,
        ];

        Item::create($params);
    }

    public function updatePlatform()
    {
        try {
            $this->validate();
            $params = [
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'show_profile' => $this->show_profile,
                'type' => $this->type,
                'business_sector_id' => $this->sector,
                'link' => $this->link
            ];
            Platform::where('id', $this->idPlatform)->update($params);


            if ($this->logoImage) {
                $platform = Platform::find($this->idPlatform);
                if ($platform->logoImage) {
                    Storage::disk('public2')->delete($platform->logoImage->url);
                }
                $imagePath = $this->logoImage->store('business-sectors/' . Platform::IMAGE_TYPE_LOGO, 'public2');
                $platform->logoImage()->delete();
                $platform->logoImage()->create([
                    'url' => $imagePath,
                    'type' => Platform::IMAGE_TYPE_LOGO,
                ]);
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Platform'));
        }
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Updated Successfully'));

    }

    public function storePlatform()
    {
        try {
            $this->validate();
            $params = [
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'show_profile' => $this->show_profile,
                'business_sector_id' => $this->sector,
                'type' => $this->type,
                'link' => $this->link
            ];
            $platform = Platform::create($params);

            if ($this->logoImage) {
                $imagePath = $this->logoImage->store('business-sectors/' . Platform::IMAGE_TYPE_LOGO, 'public2');
                $platform->logoImage()->create([
                    'url' => $imagePath,
                    'type' => Platform::IMAGE_TYPE_LOGO,
                ]);
            }
            foreach (['name', 'description'] as $translation) {
                TranslaleModel::create(
                    [
                        'name' => TranslaleModel::getTranslateName($platform, $translation),
                        'value' => $this->{$translation} . ' AR',
                        'valueFr' => $this->{$translation} . ' FR',
                        'valueEn' => $this->{$translation} . ' EN',
                        'valueEs' => $this->{$translation} . ' ES',
                        'valueTr' => $this->{$translation} . ' TR',
                        'valueRu' => $this->{$translation} . ' Ru',
                        'valueDe' => $this->{$translation} . ' De',
                    ]);
            }
            if ($this->useCoupons) {
                $this->createProduct($platform);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_create_update', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Platform!!') . ' ' . $exception->getMessage());
        }
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Created Successfully!!') . ' ' . $platform->name);
    }


    public function render()
    {
        $params = ['platform' => Platform::find($this->idPlatform)];
        return view('livewire.platform-create-update', $params)->extends('layouts.master')->section('content');
    }
}
