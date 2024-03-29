<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'positions_id',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    /**
     * Get the phone associated with the user.
     */
    public function position(): HasOne
    {
        return $this->hasOne(Position::class, 'id');
    }

    public static function img($fullFilePath)
    {
        $image = imagecreatefromjpeg($fullFilePath);

        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);

        $targetWidth = 70;
        $targetHeight = 70;

        $sourceX = max(0, ($sourceWidth - $targetWidth) / 2);
        $sourceY = max(0, ($sourceHeight - $targetHeight) / 2);

        $croppedImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagecopyresampled($croppedImage, $image, 0, 0, $sourceX, $sourceY, $targetWidth, $targetHeight, $targetWidth, $targetHeight);

        $outputFileName = $fullFilePath;
        imagejpeg($croppedImage, $outputFileName, 70);

        imagedestroy($image);
        imagedestroy($croppedImage);
    }

    public static function copressImg($fullFilePath)
    {
        $kay = config('app.api_tinify', 'Kay');
        \Tinify\setKey("$kay");

        $source = \Tinify\fromFile($fullFilePath);
        $source->toFile($fullFilePath);
    }
}
