<?php

namespace App\Repository;

use App\Models\Setting;
use App\Models\User;
use App\Repository\Interface\SettingInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingRepository implements SettingInterface
{

    public function getUserSetting(): Setting
    {
        $user = Auth::user();
        return Setting::firstOrNew(['user_id' => $user->id]);
    }

    public function updateOrCreateSetting(array $data)
    {
        try {
            return Setting::updateOrCreate(
                ['user_id' => $data['user_id']], // Kondisi pencarian
                [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'],
                    'address' => $data['address'],
                    'is_complete' => $data['is_complete'],
                    'province_id' => $data['province_id'],
                    'regency_id' => $data['regency_id'],
                    'district_id' => $data['district_id'],
                    'village_id' => $data['village_id'],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error updating or creating setting: ' . $e->getMessage());
            return null; // Bisa melempar exception atau null tergantung kebutuhan
        }
    }

    public function createSetting(array $data): Setting
    {
        return Setting::create($data);
    }

    public function saveSetting(array $data, $avatar = null): Setting
    {
        try {
            $user = Auth::user();

            if ($avatar) {
                $data['avatar'] = $this->saveAvatar($avatar);
            }

            $setting = Setting::updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            return $setting; // Mengembalikan instance Setting
        } catch (Exception $e) {
            throw new Exception('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    private function saveAvatar($avatar): string
    {
        return $avatar->storeAs('setting/avatar', $avatar->hashName(), 'public');
    }

    public function deleteAvatar(): bool
    {
        try {
            $user = Auth::user();
            $setting = Setting::where('user_id', $user->id)->first();

            if ($setting && $setting->avatar) {
                // Hapus file dari storage
                Storage::disk('public')->delete($setting->avatar);

                // Hapus avatar dari database
                $setting->update(['avatar' => null]);

                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception('Gagal menghapus avatar: ' . $e->getMessage());
        }
    }
}
