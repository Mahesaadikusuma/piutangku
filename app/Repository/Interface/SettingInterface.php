<?php

namespace App\Repository\Interface;

use App\Models\Setting;

interface SettingInterface
{
    public function getUserSetting(): Setting;
    public function updateOrCreateSetting(array $data);
    public function createSetting(array $data): Setting;
    public function saveSetting(array $data, $avatar = null): Setting;
    public function deleteAvatar(): bool;
}
