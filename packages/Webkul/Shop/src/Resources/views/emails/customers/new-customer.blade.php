@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $customer->name]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.emails.customers.registration.greeting')
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;">
        @lang('Your account has been created. Your account details are below:')
    </p>

    <p style="font-size: 16px;color: #384860;line-height: 24px;">
        @lang('Usename/Email') : <span>{{ $customer->email }}</span>
    </p>

    <p style="font-size: 16px;color: #384860;line-height: 24px;">
        @lang('Password') : {{ $password }}
    </p>

    <div style="display: flex;margin-bottom: 95px">
        <a
            href="{{ route('shop.customer.session.index') }}"
            style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;"
        >
            @lang('shop::app.emails.customers.registration.sign-in')
        </a>
    </div>
@endcomponent