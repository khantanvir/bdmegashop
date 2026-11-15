<?php

namespace App\Http\Controllers\bdmegashop\customer;

use App\Http\Controllers\Controller;
use App\Models\BdMegaShop\Customer\Customer;
use App\Models\BdMegaShop\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('customer')->check()) {
            return redirect('customer-dashboard');
        }

        $data['page_title'] = 'Customer Login & Register | BD Mega Shop';
        return view('bdmegashop/customer/login', $data);
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($request->has('remember_me')) {
            Session::put('customer_email', $request->input('email'));
            Session::put('customer_password', $request->input('password'));
            Session::put('customer_remember', $request->input('remember_me'));
        } else {
            Session::forget('customer_email');
            Session::forget('customer_password');
            Session::forget('customer_remember');
        }

        try {
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            if (Auth::guard('customer')->attempt($credentials, $request->has('remember_me'))) {
                $customer = Auth::guard('customer')->user();

                if ($customer->active == 1) {
                    Session::flash('success', 'Welcome back, ' . ($customer->name ?? $customer->email) . '!');
                    return redirect()->intended('customer-dashboard');
                } else {
                    Auth::guard('customer')->logout();
                    Session::flash('warning', 'Your customer account is currently inactive. Please contact support.');
                    return Redirect::back();
                }
            } else {
                Session::flash('error', 'Email and Password do not match!');
                return Redirect::back()->withInput($request->only('email'));
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong! ' . $e->getMessage());
            return Redirect::back()->withInput($request->only('email'));
        }
    }

    public function register_post(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $nameParts = explode(' ', trim($request->input('name')), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            $customer = Customer::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'role' => 'customer',
                'slug' => Str::slug($request->input('name')) . '-' . rand(1000, 9999),
                'active' => 1,
            ]);

            Auth::guard('customer')->login($customer);
            Session::flash('success', 'Registration successful! Welcome to BD Mega Shop.');
            return redirect('customer-dashboard');
        } catch (\Exception $e) {
            Session::flash('error', 'Registration failed: ' . $e->getMessage());
            return Redirect::back()->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function dashboard(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $data['page_title'] = 'My Account | BD Mega Shop';
        $data['customer'] = $customer;
        $data['orders'] = Order::with(['orderProducts.product', 'orderAddress'])
            ->where('customer_id', $customer->id)
            ->orderBy('id', 'desc')
            ->get();

        // Retrieve wishlist from session and cookie
        $sessionWishlist = (array) Session::get('wishlist', []);
        $cookieWishlist = json_decode($request->cookie('wishlist', '[]'), true) ?: [];
        $wishlistIds = array_values(array_unique(array_filter(array_merge($sessionWishlist, (array)$cookieWishlist))));

        if (empty($sessionWishlist) && !empty($wishlistIds)) {
            Session::put('wishlist', $wishlistIds);
        }

        $data['wishlist_ids'] = $wishlistIds;
        $data['wishlist_products'] = !empty($wishlistIds)
            ? \App\Models\BdMegaShop\Category\Product::with(['category', 'subCategory', 'brand', 'images', 'productDetails'])
                ->whereIn('id', $wishlistIds)
                ->where('status', 0)
                ->orderBy('id', 'desc')
                ->get()
            : collect();

        $data['active_tab'] = $request->input('tab', 'dashboard');

        return view('bdmegashop/customer/dashboard', $data);
    }

    public function update_profile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
        ]);

        $nameParts = explode(' ', trim($request->input('name')), 2);

        $customer->update([
            'first_name' => $request->input('first_name', $nameParts[0] ?? ''),
            'last_name' => $request->input('last_name', $nameParts[1] ?? ''),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('zip_code'),
            'address' => $request->input('address'),
        ]);

        Session::flash('success', 'Profile updated successfully!');
        return Redirect::back();
    }

    public function change_password(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $customer->password)) {
            Session::flash('error', 'Current password does not match!');
            return Redirect::back();
        }

        $customer->update([
            'password' => Hash::make($request->input('password')),
        ]);

        Session::flash('success', 'Password changed successfully!');
        return Redirect::back();
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        Session::flash('success', 'Logged out successfully!');
        return redirect('customer-login');
    }
}

