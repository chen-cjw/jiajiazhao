<?php
namespace App\Transformers;
use App\Model\Notice;
use League\Fractal\TransformerAbstract;

class NoticeTransformer extends TransformerAbstract
{

    public function transform(Notice $notice)
    {
        return [
            'id' => $notice->id,
            'content' => $notice->content,
            'created_at' => $notice->created_at->toDateTimeString(),
            'updated_at' => $notice->updated_at->toDateTimeString(),
        ];
    }
}