<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
    private function setSeederImage(string $image)
    {
        // あらかじめ用意した画像のパス = 
        $source = database_path('seeders/images/'.$image);

        $storage_path = storage_path(null); // debug /var/www/laravel-project/storage
        
        // laravel-project/storage/app/public/images/question
        // var/www/storage~
        // 再配置する画像のディレクトリ(storage/public/images/question/seedimages)
        $public_image_directory_path = str_replace('public/', 'storage/app/public/',public_path('images/question/seed_images/'));
        // アクセス先の画像のパス(DBに保存するパス)
        $access_image_path = 'images/question/seed_images/'.$image;
        // 再配置する画像のパス（アクセス先）
        $dest = $public_image_directory_path.$image;

        // dump('dest:'. $dest);
        // dump('storage_path: '.$storage_path);
        // dump($access_image_path);
        // dd($public_image_directory_path);

        // seederの共通箇所で一度のみ実行のほうがいいかも
        if(!File::exists($public_image_directory_path))
        {
            File::makeDirectory($public_image_directory_path);
        }

        if (!File::exists($dest)) {
            File::copy($source, $dest);
        }

        return $access_image_path;
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
