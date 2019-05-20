<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const DESCRIPTION = 'Description is the pattern of narrative development that aims to make 
                        vivid a place, object, character, or group. Description is one of four 
                        rhetorical modes, along with exposition, argumentation, and narration.';
    const ATTACHMENT = 'default.jpg';
    const ATTACHMENT_PATH = '/uploads/attachments';

    protected $fillable = [
        'user_id', 'category_id', 'tag_id', 'title', 'description', 'attachment'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    protected $appends = [
        'attachmentUrl'
    ];

    public function getAttachmentUrlAttribute()
    {
        return asset(self::ATTACHMENT_PATH.'/'.$this->attachment);
    }
}
