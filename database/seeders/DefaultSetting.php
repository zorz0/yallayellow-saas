<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Currency;
use App\Models\Setting;

class DefaultSetting extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::where('type', 'super admin')->first();
        $superAdminSetting = [
            "logo_dark" => "storage/uploads/logo/logo-dark.png",
            "logo_light" => "storage/uploads/logo/logo-light.png",
            "favicon" => "storage/uploads/logo/favicon.png",
            "title_text" => !empty(env('APP_NAME')) ? env('APP_NAME') : 'eCommerceGo SaaS',
            "footer_text" => "Copyright © ".(!empty(env('APP_NAME')) ? env('APP_NAME') : 'eCommerceGo SaaS'),
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "SITE_RTL" => "off",
            "display_landing" => "on",
            "SIGNUP" => "on",
            "email_verification" => "off",
            "color" => "theme-3",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",

            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,csv,svg,pdf",
            "local_storage_max_upload_size" => "2048000",
            's3_key' => '',
            's3_secret' => '',
            's3_region' => '',
            's3_bucket' => '',
            's3_endpoint' => '',
            's3_max_upload_size' => '',
            's3_storage_validation' => '',
            'wasabi_key' => '',
            'wasabi_secret' => '',
            'wasabi_region' => '',
            'wasabi_bucket' => '',
            'wasabi_url' => '',
            'wasabi_root' => '',
            'wasabi_max_upload_size' => '',
            'wasabi_storage_validation' => '',

            "CURRENCY_NAME" => "USD",
            "CURRENCYCURRENCY" => "$",
            "currency_format" => "1",
            "defult_currancy" => "USD",
            "defult_language" => "en",
            "defult_timezone" => "Asia/Kolkata",

            // for cookie
            'enable_cookie'=>'on',
            'cookie_logging'=>'on',
            'necessary_cookies'=>'on',
            'cookie_title'=>'We use cookies!',
            'cookie_description'=>'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
            'strictly_cookie_title'=>'Strictly necessary cookies',
            'strictly_cookie_description'=>'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'more_information_description'=>'For any queries in relation to our policy on cookies and your choices, please contact us',
            "more_information_title" => "",
            'contactus_url'=>'#',

        ];

        foreach($superAdminSetting as $key =>  $value){
            // Define the data to be updated or inserted
            $data = [
                'name' => $key,
                'theme_id' => 'grocery',
                'store_id' => $superAdmin->current_store,
                'created_by' => $superAdmin->id,
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => (string) $value, 'created_at' => now(), 'updated_at' => now()]);
        }

        // currency code

        $currencys = [
            ['Leke', 'ALL', 'Lek'],
            ['Dollars', 'USD', '$'],
            ['Afghanis', 'AFN', '؋'],
            ['Pesos', 'ARS', '$'],
            ['Guilders', 'AWG', 'ƒ'],
            ['Dollars', 'AUD', '$'],
            ['New Manats', 'AZN', 'ман'],
            ['Dollars', 'BSD', '$'],
            ['Dollars', 'BBD', '$'],
            ['Rubles', 'BYR', 'p.'],
            ['Euro', 'EUR', '€'],
            ['Dollars', 'BZD', 'BZ$'],
            ['Dollars', 'BMD', '$'],
            ['Bolivianos', 'BOB', '$b'],
            ['Convertible Marka', 'BAM', 'KM'],
            ['Pula', 'BWP', 'P'],
            ['Leva', 'BGN', 'лв'],
            ['Reais', 'BRL', 'R$'],
            ['Pounds', 'GBP', '£'],
            ['Dollars', 'BND', '$'],
            ['Riels', 'KHR', '៛'],
            ['Dollars', 'CAD', '$'],
            ['Dollars', 'KYD', '$'],
            ['Pesos', 'CLP', '$'],
            ['Yuan Renminbi', 'CNY', '¥'],
            ['Pesos', 'COP', '$'],
            ['Colón', 'CRC', '₡'],
            ['Kuna', 'HRK', 'kn'],
            ['Pesos', 'CUP', '₱'],
            ['Koruny', 'CZK', 'Kč'],
            ['Kroner', 'DKK', 'kr'],
            ['Pesos', 'DOP', 'RD$'],
            ['Dollars', 'XCD', '$'],
            ['Pounds', 'EGP', '£'],
            ['Colones', 'SVC', '$'],
            ['Pounds', 'FKP', '£'],
            ['Dollars', 'FJD', '$'],
            ['Cedis', 'GHC', '¢'],
            ['Pounds', 'GIP', '£'],
            ['Quetzales', 'GTQ', 'Q'],
            ['Pounds', 'GGP', '£'],
            ['Dollars', 'GYD', '$'],
            ['Lempiras', 'HNL', 'L'],
            ['Dollars', 'HKD', '$'],
            ['Forint', 'HUF', 'Ft'],
            ['Kronur', 'ISK', 'kr'],
            ['Rupees', 'INR', 'Rp'],
            ['Rupiahs', 'IDR', 'Rp'],
            ['Rials', 'IRR', '﷼'],
            ['Pounds', 'IMP', '£'],
            ['New Shekels', 'ILS', '₪'],
            ['Dollars', 'JMD', 'J$'],
            ['Yen', 'JPY', '¥'],
            ['Pounds', 'JEP', '£'],
            ['Tenge', 'KZT', 'лв'],
            ['Won', 'KPW', '₩'],
            ['Won', 'KRW', '₩'],
            ['Soms', 'KGS', 'лв'],
            ['Kips', 'LAK', '₭'],
            ['Lati', 'LVL', 'Ls'],
            ['Pounds', 'LBP', '£'],
            ['Dollars', 'LRD', '$'],
            ['Switzerland Francs', 'CHF', 'CHF'],
            ['Litai', 'LTL', 'Lt'],
            ['Denars', 'MKD', 'ден'],
            ['Ringgits', 'MYR', 'RM'],
            ['Rupees', 'MUR', '₨'],
            ['Pesos', 'MXN', '$'],
            ['Tugriks', 'MNT', '₮'],
            ['Meticais', 'MZN', 'MT'],
            ['Dollars', 'NAD', '$'],
            ['Rupees', 'NPR', '₨'],
            ['Guilders', 'ANG', 'ƒ'],
            ['Dollars', 'NZD', '$'],
            ['Cordobas', 'NIO', 'C$'],
            ['Nairas', 'NGN', '₦'],
            ['Krone', 'NOK', 'kr'],
            ['Rials', 'OMR', '﷼'],
            ['Rupees', 'PKR', '₨'],
            ['Balboa', 'PAB', 'B/.'],
            ['Guarani', 'PYG', 'Gs'],
            ['Nuevos Soles', 'PEN', 'S/.'],
            ['Pesos', 'PHP', 'Php'],
            ['Zlotych', 'PLN', 'zł'],
            ['Rials', 'QAR', '﷼'],
            ['New Lei', 'RON', 'lei'],
            ['Rubles', 'RUB', 'руб'],
            ['Pounds', 'SHP', '£'],
            ['Riyals', 'SAR', '﷼'],
            ['Dinars', 'RSD', 'Дин.'],
            ['Rupees', 'SCR', '₨'],
            ['Dollars', 'SGD', '$'],
            ['Dollars', 'SBD', '$'],
            ['Shillings', 'SOS', 'S'],
            ['Rand', 'ZAR', 'R'],
            ['Rupees', 'LKR', '₨'],
            ['Kronor', 'SEK', 'kr'],
            ['Dollars', 'SRD', '$'],
            ['Pounds', 'SYP', '£'],
            ['New Dollars', 'TWD', 'NT$'],
            ['Baht', 'THB', '฿'],
            ['Dollars', 'TTD', 'TT$'],
            ['Lira', 'TRY', '₺'],
            ['Liras', 'TRL', '£'],
            ['Dollars', 'TVD', '$'],
            ['Hryvnia', 'UAH', '₴'],
            ['Pesos', 'UYU', '$U'],
            ['Sums', 'UZS', 'лв'],
            ['Bolivares Fuertes', 'VEF', 'Bs'],
            ['Dong', 'VND', '₫'],
            ['Rials', 'YER', '﷼'],
            ['Zimbabwe Dollars', 'ZWD', 'Z$'],
            ['Bahraini Dinar', 'BHD', '$'],
            ['Turkish lira', 'TL', '₺'],
            ['CFA', 'CFA', 'CFA'],
        ];

        foreach ($currencys as  $currency) {
            $ckeck = Currency::where('code',$currency[1])->first();
            if(empty($ckeck))
            {
                $currency_data       = new Currency();
                $currency_data->name = $currency[0];
                $currency_data->code = $currency[1];
                $currency_data->symbol = $currency[2];
                $currency_data->timestamps = false;
                $currency_data->save();
            }
        }
    }
}
