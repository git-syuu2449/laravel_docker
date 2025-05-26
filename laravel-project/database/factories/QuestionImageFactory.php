<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionImage>
 */
class QuestionImageFactory extends Factory
{

    /**
     * Seederで利用する画像のパス
     * @var string
     */
    public $seeder_image_path = 'seeders/images/';

    /**
     * seederの画像から再配置するパス
     * @var 
     */
    public $public_image_path = 'seed_images/';

    /**
     * 画像の再配置を行い、パスを返却する
     * @return string
     */
    private function setSeederImage($image)
    {
        $source = database_path('seeders/images/'.$image);
        $dest = public_path('seed_images/'.$image);
        $public_image_path = public_path('seed_images/');


        // seederの共通箇所で一度のみ実行のほうがいいかも
        if(!File::exists($public_image_path))
        {
            File::makeDirectory($public_image_path);
        }

        if (!File::exists($dest)) {
            File::copy($source, $dest);
        }

        return $dest;
    }


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 画像登録する画像
        $seeder_images = [
            'test_1.jpeg',
            'test_2.jpeg',
            'test_1.webp',
            'test_2.webp',
        ];
        
        return [
            'image' => $this->setSeederImage(Arr::random($seeder_images)),
        ];
    }

}
