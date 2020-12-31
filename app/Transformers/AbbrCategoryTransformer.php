<?php
namespace App\Transformers;
use App\Model\AbbrCategory;
use League\Fractal\TransformerAbstract;

class AbbrCategoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['abbr'];

    public function transform(AbbrCategory $category)
    {
        return [
            'id' => $category->id,
            'abbr' => $category->abbr,
            'sort' => $category->sort,
            'parent_id' => $category->parent_id,
            'sub_set' => AbbrCategory::where('parent_id',$category->id)->orderBy('sort','desc')->get(),
            'created_at' => $category->created_at->toDateTimeString(),
            'updated_at' => $category->updated_at->toDateTimeString(),
        ];
    }

    public function includeAbbr(AbbrCategory $abbrCategory)
    {
        return $this->item($abbrCategory->abbr,new AbbrCategoryTransformer());
    }

}