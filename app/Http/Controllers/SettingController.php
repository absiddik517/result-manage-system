<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
  public function index()
  {
    $institute = Cache::get('logo');
    //dd($institute);
    $settings = Setting::select("key", "value")->get();
    $settings = $this->parseSettings($settings);
    $admit_templates = $this->get_templates();
    return inertia("Academic/Setting", compact("settings", "admit_templates"));
  }

  public function update_institute(Request $request)
  {
    $data = request()->validate([
      "name" => "required",
      "short_name" => "required",
      "line_1" => "nullable",
      "line_2" => "nullable",
      "line_3" => "nullable",
      "line_4" => "nullable",
    ]);
    try {
      Setting::where("key", "institute")->update([
        "value" => json_encode($data),
      ]);
      Cache::forget('institute');
      $toast = [
        "message" => "Setting updated!",
        "type" => "success",
      ];
    } catch (\Excetion $e) {
      $toast = [
        "message" => $e->getMessage(),
        "type" => "error",
      ];
    }
    return redirect()
      ->back()
      ->with("toast", $toast);
  }
  public function update_pass_mark(Request $request)
  {
    $data = request()->validate([
      "mark" => "required|gte:33|lte:60",
    ]);
    try {
      Setting::where("key", "pass_mark")->update([
        "value" => $data["mark"],
      ]);
      Cache::forget('pass_mark');
      $toast = [
        "message" => "Setting updated!",
        "type" => "success",
      ];
    } catch (\Excetion $e) {
      $toast = [
        "message" => $e->getMessage(),
        "type" => "error",
      ];
    }
    return redirect()
      ->back()
      ->with("toast", $toast);
  }
  
  public function update_admit_template(Request $request)
  {
    $data = request()->validate([
      "template" => "required",
    ]);
    try {
      Setting::where("key", "admit_template")->update([
        "value" => $data["template"],
      ]);
      Cache::forget('admit_template');
      $toast = [
        "message" => "Setting updated!",
        "type" => "success",
      ];
    } catch (\Excetion $e) {
      $toast = [
        "message" => $e->getMessage(),
        "type" => "error",
      ];
    }
    return redirect()
      ->back()
      ->with("toast", $toast);
  }

  public function update_logo(Request $req)
  {
    //dd();
    if ($req->hasFile("logo")) {
      $path = $req->logo->store("uploads", "public");
      $setting = Setting::where("key", "logo")->first();
      if (Storage::disk("public")->exists($setting->value) && $setting->value !== "uploads/default.png") {
        Storage::disk("public")->delete($setting->value);
      }
      $setting->update([
        "value" => $path,
      ]);
    }
    Cache::forget('logo');
    $toast = [
      "message" => "Institute logo has <kbd>created</kbd> successful!",
      "type" => "success",
    ];
    return redirect()
      ->back()
      ->with("toast", $toast);
  }

  private function parseSettings($settings)
  {
    $json_fields = ["institute"];
    $image_field = ["logo"];
    $result = [];
    foreach ($settings as $setting) {
      if (in_array($setting->key, $json_fields)) {
        $ins = json_decode($setting->value, true);
        $result[$setting->key] = $ins;
      } elseif (in_array($setting->key, $image_field)) {
        $result[$setting->key] = asset("storage/" . $setting->value);
      } else {
        $result[$setting->key] = $setting->value;
      }
    }
    return $result;
  }

  public function get_templates()
  {
    $folderPath = resource_path("views/pdf/admits"); // resource_path points to the resources directory
    if (!File::exists($folderPath)) return [];
    $files = File::files($folderPath);
    $pdfFiles = array_filter($files, function ($file) {
      return $file->getExtension() === "php";
    });
    $fileNames = array_map(function ($file) {
      return explode('.', $file->getFilename())[0];
    }, $pdfFiles);

    return $fileNames;
  }
}
