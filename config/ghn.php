<?php

return [
    'api_url' => env('GHN_API_URL', 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2'),
    'token' => env('GHN_TOKEN', 'test-token-ghn-sportstore'),
    'shop_id' => env('GHN_SHOP_ID', '123456'),
    'client_phone' => env('GHN_SHOP_PHONE', '19008888'),
    'from_district_id' => env('GHN_FROM_DISTRICT_ID', 1442), // Cau Giay, Ha Noi
    'from_ward_code' => env('GHN_FROM_WARD_CODE', '20101'),
];
