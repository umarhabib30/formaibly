<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        $pageTitle = 'Pricing Plan';
        $plans = Plan::searchable(['name'])->orderBy('id','desc')->paginate(getPaginate());
        return view('Admin::plan.index', compact('pageTitle', 'plans'));
    }

    public function subscriptions()
    {
        $pageTitle = 'Subscriptions History';
        $subscriptions = Subscription::with('plan')->searchable(['plan.name'])->where('status', 1)->latest()->get();
        return view('Admin::subscription.index', compact('pageTitle', 'subscriptions'));
    }


    public function create()
    {
        $pageTitle = 'Create Plan';
        return view('Admin::plan.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => ['required', 'string', Rule::unique('plans', 'name')],
            'price'             => 'nullable|numeric|min:0',
            'period'            => 'required|in:monthly,yearly',
            'input_limit'    => 'required|integer|min:1',
            'short_description' => 'required|string|max:500',
            'credit'            => 'nullable|integer|min:0',
            'is_popular'        => 'nullable|in:0,1',
            'features'          => 'required|array|min:1',
            'features.*'        => 'string|max:255',
        ]);

        try {
            $plan                 = new Plan();
            $plan->name           = $request->name;
            $plan->price          = $request->price ?? 0.00;
            $plan->period         = $request->period;
            $plan->input_limit = $request->input_limit;
            $plan->credit         = $request->credit ? $request->credit : 0;
            $plan->short_description    = $request->short_description;
            $plan->features       = $request->features;
            $plan->is_popular     = ($request->is_popular) ? 1 : 0;
            $plan->save();

            if ($request->is_popular) {
                $allPlans = $plan->whereNot('id', $plan->id)->get();
                foreach ($allPlans as $key => $value) {
                    $value->is_popular = 0;
                    $value->save();
                }
            }

            $notify[] = ['success', 'Plan created successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', "Something went wrong. Plan could not be created."];
            return back()->withNotify($notify);
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Update Plan';
        $plan = Plan::where('id', $id)->first();
        if (!$plan) {
            $notify[] = ['error', "Plan not found"];
            return back()->withNotify($notify);
        }
        return view('Admin::plan.edit', compact('plan', 'pageTitle'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name'              => ['required', 'string', Rule::unique('plans', 'name')->ignore($id)],
            'price'             => 'nullable|numeric|min:0',
            'period'            => 'required|in:monthly,yearly',
            'input_limit'    => 'required|integer|min:1',
            'short_description' => 'required|string|max:500',
            'credit'            => 'nullable|integer|min:0',
            'is_popular'        => 'nullable|in:0,1',
            'features'          => 'required|array|min:1',
            'features.*'        => 'string|max:255',
        ]);

        try {
            $plan = Plan::findOrFail($id);
            $plan->name           = $request->name;
            $plan->price          = $request->price ?? 0.00;
            $plan->period         = $request->period;
            $plan->input_limit = $request->input_limit;
            $plan->credit         = $request->credit ?? 0;
            $plan->short_description    = $request->short_description;
            $plan->features       = $request->features;
            $plan->is_popular     = $request->is_popular ? 1 : 0;
            $plan->save();

            if ($request->is_popular) {
                $allPlans = Plan::whereNot('id', $plan->id)->get();
                foreach ($allPlans as $key => $value) {
                    $value->is_popular = 0;
                    $value->save();
                }
            }

            $notify[] = ['success', 'Plan has been updated successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Something went wrong. Plan could not be updated.'];
            return back()->withNotify($notify);
        }
    }


    public function status($id)
    {
        $plan = Plan::where('id', $id)->first();
        if (!$plan) {
            $notify[] = ['error', "Plan not found"];
            return back()->withNotify($notify);
        }
        $plan->status = ($plan->status == 1) ? 0 : 1;
        $plan->save();
        $notify[] = ['success', 'plan status has been updated successfully'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->delete();

            $notify[] = ['success', 'Plan deleted successfully'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Something went wrong. Plan could not be deleted.'];
            return back()->withNotify($notify);
        }
    }
}
