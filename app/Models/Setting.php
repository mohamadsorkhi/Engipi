<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value'
    ];

    /**
     * @param string $key
     * @return mixed
     */
    public function scopeGetValue($query, $key)
    {
        return $query->where('key', $key)->firstOrFail()->value;
    }

    public function scopeUpdateValue($query, $key, $value)
    {
        $setting = $query->where('key', $key)->firstOrFail();
        $setting->value = $value;
        return $setting->update();
    }

    public static function updateImage($request, $key)
    {
        $setting = self::where('key', $key)->firstOrFail();
        $removeKey = "remove_" . $key;
        if ($request->has('settings.' . $key)) {
            // Get the current value of the setting
            $currentValue = $setting->value;

            $imageName = $key . time() . '.' . $request->settings[$key]->extension();
            $request->settings[$key]->move(public_path('images/settings'), $imageName);
            // Check if the current value is a file path
            if (File::exists($currentValue)) {
                // If it is, delete the file
                File::delete($currentValue);
            }
            $setting->value = 'images/settings/' . $imageName;
            return $setting->update();
        } elseif (!empty($request->$removeKey)) {
            $setting->value = NULL;
            return $setting->update();
        }
        return false;
    }

    public static function updateFile($request, $key)
    {
        $setting = self::where('key', $key)->firstOrFail();
        if ($request->has('settings.' . $key)) {
            // Get the current value of the setting
            $currentValue = $setting->value;

            $fileName = $key . time() . '.' . $request->settings[$key]->getClientOriginalName();
            $request->settings[$key]->move(public_path('files/settings'), $fileName);

            if (File::exists($currentValue)) {
                // If it is, delete the file
                File::delete($currentValue);
            }
            $setting->value = 'files/settings/' . $fileName;
            return $setting->update();
        }
        return false;
    }
}
