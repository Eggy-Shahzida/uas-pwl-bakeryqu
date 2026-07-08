<?php

/**
 * =====================================================
 * SHIPPING SERVICE - RajaOngkir Komerce API (v1)
 * =====================================================
 * Menangani komunikasi ke RajaOngkir untuk:
 * - Ambil daftar provinsi
 * - Ambil daftar kota/kabupaten berdasarkan provinsi
 * - Ambil daftar kecamatan berdasarkan kota
 * - Hitung ongkos kirim berdasarkan district tujuan
 *
 * Dokumentasi: https://rajaongkir.komerce.id (Step-by-Step Method)
 */

class ShippingService
{
    private $baseUrl;

    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = RAJAONGKIR_BASE_URL;

        $this->apiKey = RAJAONGKIR_API_KEY;
    }

    //------------------------------------------------
    // helper: kirim request ke RajaOngkir
    //------------------------------------------------
    private function request($endpoint, $method = 'GET', $params = [])
    {
        $curl = curl_init();

        $headers = [
            "key: {$this->apiKey}"
        ];

        if ($method === 'GET') {

            $url = $this->baseUrl . $endpoint;

            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }

            curl_setopt($curl, CURLOPT_URL, $url);

        } else {

            curl_setopt($curl, CURLOPT_URL, $this->baseUrl . $endpoint);

            curl_setopt($curl, CURLOPT_POST, true);

            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

            $headers[] = "Content-Type: application/x-www-form-urlencoded";

        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_TIMEOUT, 15);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $curlError = curl_error($curl);

        curl_close($curl);

        // gagal koneksi
        if ($curlError) {
            return [
                'success' => false,
                'message' => "Koneksi ke RajaOngkir gagal: {$curlError}",
                'data' => []
            ];
        }

        $decoded = json_decode($response, true);

        // response tidak valid / gagal
        if ($httpCode !== 200 || !isset($decoded['data'])) {
            return [
                'success' => false,
                'message' => $decoded['meta']['message'] ?? 'Gagal mengambil data dari RajaOngkir.',
                'data' => []
            ];
        }

        return [
            'success' => true,
            'message' => 'OK',
            'data' => $decoded['data']
        ];
    }

    //------------------------------------------------
    // ambil semua provinsi
    //------------------------------------------------
    public function getProvinces()
    {
        return $this->request('/destination/province');
    }

    //------------------------------------------------
    // ambil kota/kabupaten berdasarkan id provinsi
    //------------------------------------------------
    public function getCities($provinceId)
    {
        return $this->request("/destination/city/{$provinceId}");
    }

    //------------------------------------------------
    // ambil kecamatan berdasarkan id kota
    //------------------------------------------------
    public function getDistricts($cityId)
    {
        return $this->request("/destination/district/{$cityId}");
    }

    //------------------------------------------------
    // hitung ongkos kirim ke district tujuan
    //------------------------------------------------
    public function calculateCost($destinationDistrictId, $weight, $courier = 'jne')
    {
        $params = [
            'origin' => STORE_ORIGIN_ID,
            'destination' => $destinationDistrictId,
            'weight' => $weight,
            'courier' => $courier,
            'price' => 'lowest'
        ];

        return $this->request('/calculate/district/domestic-cost', 'POST', $params);
    }
}