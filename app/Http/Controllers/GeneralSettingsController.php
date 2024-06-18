<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\GeneralSettings;
use App\Http\Requests\GeneralSettingsRequest;

class GeneralSettingsController extends Controller
{
    public function show(GeneralSettings $settings){
        
        $this->authorize('boss');

        return view('settings.show', [
            'cor' => $settings->cor,
        ]);
    }

    public function update(GeneralSettingsRequest $request,GeneralSettings $settings){
        
        $this->authorize('boss');
        
        $settings->cor = $request->input('cor');       
        $settings->save();
        return redirect()->back();
    }
}
