<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'tel_num' => self::mobilephone(),
            'address' => $this->faker->address,
            'is_active' => Customer::ACTIVE,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
            ];
        });
    }

    /*
    * phone number
    */
    public static function mobilephone()
    {
        return self::prefixPhone().self::postfixPhone();
    }

    private static $mobilePhone = [
        '086',
        '096',
        '097',
        '098',
        '032',
        '033',
        '034',
        '035',
        '036',
        '037',
        '038',
        '039',
        '091',
        '094',
        '088',
        '083',
        '084',
        '085',
        '081',
        '082',
        '089',
        '090',
        '093',
        '070',
        '079',
        '077',
        '076',
        '078',
        '092',
        '056',
        '058',
        '099',
        '059',
    ];

    private static function prefixPhone()
    {
        return self::array_rand(self::$mobilePhone);
    }

    private static function postfixPhone($count = 7)
    {
        $postfix = '';
        $numbers = '0123456789';
        for ($i = 0; $i < $count; $i++) {
            $postfix .= $numbers[mt_rand(0, $count - 1)];
        }

        return $postfix;
    }

    /**
     * @return string
     */
    public static function array_rand(array $items, $num = 1, $array = false, $glue = ',')
    {
        $return_value = false;
        if ($num > 1) {
            for ($i = 0; $i < $num; $i++) {
                $return_value[] = $items[array_rand($items)];
            }

            if ($array) {
                return $return_value;
            }
            return implode($glue, $return_value);
        }
        return mb_convert_encoding(trim($items[array_rand($items)]), "UTF-8");
    }
}
