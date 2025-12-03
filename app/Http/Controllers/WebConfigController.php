<?php

namespace App\Http\Controllers;

use App\Models\WebConfig;
use App\Models\Especialidad; // <--- IMPORTAR MODELO
use Illuminate\Http\Request;

class WebConfigController extends Controller
{
    // Vista Pública (Welcome)
    public function home()
    {
        $web = WebConfig::pluck('value', 'key')->all();
        // Cargamos las especialidades de la BD
        $especialidades = Especialidad::all(); 
        
        return view('welcome', compact('web', 'especialidades'));
    }

    // Vista Admin (CMS)
    public function index()
    {
        $configs = WebConfig::all();
        // También las enviamos al panel de admin para poder borrarlas
        $especialidades = Especialidad::all();

        return view('admin.web.edit', compact('configs', 'especialidades'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            WebConfig::where('key', $key)->update(['value' => $value]);
        }
        return back()->with('success', 'Textos actualizados correctamente.');
    }
}