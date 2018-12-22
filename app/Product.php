<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['status', 'title', 'article','attributes','description','image','is_new','is_popular', 'parent_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_product');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', 1);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', 1);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $article
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArticle($query, $article)
    {
        return $query->where('article', 'like', '%' . $article . '%');
    }

}
