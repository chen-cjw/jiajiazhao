<?php
namespace App\Transformers;
use App\Model\CardCategory;
use League\Fractal\TransformerAbstract;

class CardCategoryTransformer extends TransformerAbstract
{

    public function transform(CardCategory $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'sort' => $category->sort,
            'created_at' => $category->created_at->toDateTimeString(),
            'updated_at' => $category->updated_at->toDateTimeString(),
        ];
    }
}