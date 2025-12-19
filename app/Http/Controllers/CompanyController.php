<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function edit()
    {
        $company = Company::first();
        if (!$company) {
            $company = new Company();
            $company->name = 'QUALITY CARTONS (PVT.) LTD.';
            $company->address = 'Plot # 46, Sector 24 Korangi Industrial Area, Karachi.';
            $company->save();
        }
        return view('company.setup', compact('company'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string',
        ]);

        $company = Company::firstOrFail();
        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path && Storage::exists('public/' . $company->logo_path)) {
                Storage::delete('public/' . $company->logo_path);
            }
            $path = $request->file('logo')->store('company-logos', 'public');
            $data['logo_path'] = $path;
        }

        $company->update($data);

        return redirect()->route('company.setup')->with('success', 'Company details updated successfully.');
    }
}
