<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */


    public function toArray($request)
    {
        return [
//            'slug' =>$this->resource['slug'],
//            'caption' => $this->resource['caption'],
//            'comment_able' => $this->resource['comment_able'],

            'slug' => $request->slug,
            'caption' => $request->caption,
            'comment_able' => $request->comment_able,

//            'id'=>$this->get('id'),
//            'slug' => $this->get('slug'),
//            'caption' => $this->get('caption'),
//            'comment_able' => $this->get('comment_able'),

//            'id'=>$this->id,
//            'slug' => $this->slug,
//            'caption' => $this->caption,
//            'comment_able' => $this->comment_able,
        ];
    }
}
