<?php

namespace App\Helpers;

class ShippingHelper
{
    // Zone map với danh sách tỉnh/thành phố
    const ZONE_MAP = [
        'ZONE_1' => [
            'Tp Hồ Chí Minh',
            'Thành phố Hồ Chí Minh',
        ],
        'ZONE_2' => [
            'Tỉnh Đồng Nai',
            'Tỉnh Tây Ninh',
            'Tỉnh Vĩnh Long',
            'Tỉnh Đồng Tháp',
            'Tỉnh An Giang',
            'Tp Cần Thơ',
            'Thành phố Cần Thơ',
            'Tỉnh Cà Mau',
        ],
        'ZONE_3' => [
            'Thành phố Huế',
            'Tp Đà Nẵng',
            'Thành phố Đà Nẵng',
            'Tỉnh Quảng Ngãi',
            'Tỉnh Khánh Hòa',
            'Tỉnh Gia Lai',
            'Tỉnh Đắk Lắk',
            'Tỉnh Lâm Đồng',
        ],
        'ZONE_4' => [
            'Thành phố Hà Nội',
            'Tp Hải Phòng',
            'Thành phố Hải Phòng',
            'Tỉnh Bắc Ninh',
            'Tỉnh Quảng Ninh',
            'Tỉnh Hưng Yên',
            'Tỉnh Ninh Bình',
            'Tỉnh Cao Bằng',
            'Tỉnh Tuyên Quang',
            'Tỉnh Lào Cai',
            'Tỉnh Thái Nguyên',
            'Tỉnh Lạng Sơn',
            'Tỉnh Phú Thọ',
            'Tỉnh Điện Biên',
            'Tỉnh Lai Châu',
            'Tỉnh Sơn La',
            'Tỉnh Thanh Hóa',
            'Tỉnh Nghệ An',
            'Tỉnh Hà Tĩnh',
            'Tỉnh Quảng Trị',
        ],
    ];

    // Giá ship theo zone
    const ZONE_PRICES = [
        'ZONE_1' => 20000,
        'ZONE_2' => 27000,
        'ZONE_3' => 32000,
        'ZONE_4' => 37000,
    ];

    // Thời gian giao hàng theo zone (số ngày)
    const ZONE_DELIVERY_DAYS = [
        'ZONE_1' => [1, 2], // 1-2 ngày
        'ZONE_2' => [2, 3], // 2-3 ngày
        'ZONE_3' => [3, 4], // 3-4 ngày
        'ZONE_4' => [4, 5], // 4-5 ngày
    ];

    // Thời gian giao hàng mặc định
    const DEFAULT_DELIVERY_DAYS = [2, 4];

    // Giá mặc định nếu không tìm thấy zone
    const DEFAULT_SHIPPING_FEE = 30000;

    /**
     * Tính thời gian giao hàng dự kiến dựa trên zone
     * 
     * @param string $provinceName Tên tỉnh/thành phố
     * @return string Thời gian giao hàng dự kiến
     */
    public static function calculateDeliveryTime($provinceName)
    {
        $zone = self::getZone($provinceName);
        
        if ($zone && isset(self::ZONE_DELIVERY_DAYS[$zone])) {
            $deliveryDays = self::ZONE_DELIVERY_DAYS[$zone];
        } else {
            $deliveryDays = self::DEFAULT_DELIVERY_DAYS;
        }
        
        $startDate = now()->addDays($deliveryDays[0]);
        $endDate = now()->addDays($deliveryDays[1]);
        
        return $startDate->format('d') . ' - ' . $endDate->format('d') . ' Tháng ' . $startDate->format('m');
    }

    /**
     * Tính phí ship dựa trên tên tỉnh/thành phố
     * 
     * @param string $provinceName Tên tỉnh/thành phố
     * @return int Phí ship
     */
    public static function calculateShippingFee($provinceName)
    {
        if (empty($provinceName)) {
            return self::DEFAULT_SHIPPING_FEE;
        }

        // Chuẩn hóa tên tỉnh để so sánh
        $normalizedProvinceName = self::normalizeProvinceName($provinceName);

        // Tìm zone của tỉnh
        foreach (self::ZONE_MAP as $zone => $provinces) {
            foreach ($provinces as $province) {
                $normalizedZoneProvince = self::normalizeProvinceName($province);
                
                // So sánh tên tỉnh (không phân biệt hoa thường, bỏ qua dấu)
                if ($normalizedProvinceName === $normalizedZoneProvince) {
                    return self::ZONE_PRICES[$zone];
                }
            }
        }

        // Nếu không tìm thấy zone, trả về giá mặc định
        return self::DEFAULT_SHIPPING_FEE;
    }

    /**
     * Lấy zone của tỉnh/thành phố
     * 
     * @param string $provinceName Tên tỉnh/thành phố
     * @return string|null Tên zone hoặc null nếu không tìm thấy
     */
    public static function getZone($provinceName)
    {
        if (empty($provinceName)) {
            return null;
        }

        $normalizedProvinceName = self::normalizeProvinceName($provinceName);

        foreach (self::ZONE_MAP as $zone => $provinces) {
            foreach ($provinces as $province) {
                $normalizedZoneProvince = self::normalizeProvinceName($province);
                
                if ($normalizedProvinceName === $normalizedZoneProvince) {
                    return $zone;
                }
            }
        }

        return null;
    }

    /**
     * Chuẩn hóa tên tỉnh để so sánh
     * 
     * @param string $provinceName
     * @return string
     */
    private static function normalizeProvinceName($provinceName)
    {
        // Chuyển về chữ thường
        $normalized = mb_strtolower($provinceName, 'UTF-8');
        
        // Loại bỏ khoảng trắng thừa
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        $normalized = trim($normalized);
        
        return $normalized;
    }

    /**
     * Lấy tất cả các zone và giá ship
     * 
     * @return array
     */
    public static function getAllZones()
    {
        return [
            'zones' => self::ZONE_MAP,
            'prices' => self::ZONE_PRICES,
            'default' => self::DEFAULT_SHIPPING_FEE,
        ];
    }
}
