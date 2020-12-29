<?php
namespace App\Transformers;
use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    //protected $availableIncludes = ['team'];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'ml_openid' => $user->ml_openid,
            'phone' => $user->phone,
            'nickname' => $user->nickname,
            'sex' => $user->sex,
            'avatar' => $user->avatar,
            'parent_id' => $user->parent_id,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
    }

    public function includeTeam(User $user)
    {
        //return $this->item($user->team,new TeamTransformer());
    }

}