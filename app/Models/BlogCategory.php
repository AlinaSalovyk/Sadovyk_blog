<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;
    use HasFactory;
    const ROOT = 1;
/**
     * Батьківська категорія
     *
     * @return BlogCategory
     */
    public function parentCategory()
    {
        //належить категорії
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

     /**
     * Приклад аксесуара (Accessor)
     *
     * @url https://laravel.com/docs/7.x/eloquent-mutator
     *
     * @return string
     */
    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title
            ?? ($this->isRoot()
                ? 'Корінь'
                : '???');

        return $title;
    }

    /**
     * Перевірка чи об'єкт є кореневим
     *
     * @return bool
     */
    public function isRoot()
    {
        return $this->id === BlogCategory::ROOT;
    }

    protected $fillable
        = [
            'title',
            'slug',
            'parent_id',
            'description',
        ];
}

