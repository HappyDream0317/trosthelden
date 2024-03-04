<?php

return [
    'plangroups' => [
        'b2b' => env('BILLWERK_PLANGROUP_B2B', ''),
        'standard' => env('BILLWERK_PLANGROUP_STANDARD', ''),
    ],
    'plans' => [
        'standard' => [
            '1_months' => env('BILLWERK_PLAN_1_MONTHS', ''),
            '3_months' => env('BILLWERK_PLAN_3_MONTHS', ''),
            '6_months' => env('BILLWERK_PLAN_6_MONTHS', ''),
        ],
        'b2b' => [
            '50x_1_months' => env('BILLWERK_PLAN_B2B_50X_1_MONTHS', ''),
            '100x_1_months' => env('BILLWERK_PLAN_B2B_100X_1_MONTHS', ''),
            '200x_1_months' => env('BILLWERK_PLAN_B2B_200X_1_MONTHS', ''),
            '5x_6_months' => env('BILLWERK_PLAN_B2B_5X_6_MONTHS', ''),
            '10x_6_months' => env('BILLWERK_PLAN_B2B_10X_6_MONTHS', ''),
            '20x_6_months' => env('BILLWERK_PLAN_B2B_20X_6_MONTHS', ''),
            'flatrate_1_years' => env('BILLWERK_PLAN_B2B_FLATRATE_1_YEARS', ''),
        ]
    ],
    'codes' => [
        '1_months' => env('BILLWERK_COUPON_1_MONTHS', ''),
        '3_months' => env('BILLWERK_COUPON_3_MONTHS', ''),
        '6_months' => env('BILLWERK_COUPON_6_MONTHS', ''),
    ],
    'discount' => [
        '100_1_months' => env('BILLWERK_DISCOUNT_100_1_MONTHS', ''),
        '100_3_months' => env('BILLWERK_DISCOUNT_100_3_MONTHS', ''),
        '100_6_months' => env('BILLWERK_DISCOUNT_100_6_MONTHS', ''),
    ],
    'auth' => [
        'client_id' => env('BILLWERK_CLIENT_ID', ''),
        'client_token' => env('BILLWERK_CLIENT_TOKEN', ''),
        'public_api_key' => env('BILLWERK_PUBLIC_API_KEY', ''),
    ],
    'routes' => [
        'auth' => env('BILLWERK_ROUTE_AUTH', ''),
        'contract' => env('BILLWERK_ROUTE_CONTRACT', ''),
        'planVariant' => env('BILLWERK_ROUTE_PLANVARIANT', ''),
        'customer' => env('BILLWERK_ROUTE_CUSTOMER', ''),
        'signup' => env('BILLWERK_ROUTE_SIGNUP', ''),
        'subscription' => env('BILLWERK_ROUTE_SUBSCRIPTION', ''),
        'countries' => env('BILLWERK_ROUTE_COUNTRIES', ''),
        'orders' => env('BILLWERK_ROUTE_ORDERS', ''),
        'planGroups' => env('BILLWERK_ROUTE_PLANGROUPS', ''),
        'coupons' => env('BILLWERK_ROUTE_COUPONS', ''),
        'discounts' => env('BILLWERK_ROUTE_DISCOUNTS', ''),
    ]
];
