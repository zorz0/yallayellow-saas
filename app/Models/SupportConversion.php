<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'description',
        'attachments',
        'sender',
        'theme_id',
        'store_id',
        'customer_id'
    ];

    public function replyBy(){
        if($this->sender=='user'){
            // return $this->ticket;
            return $this->hasOne(Customer::class,'id','customer_id')->first();
        }
        else{
            return $this->hasOne(User::class,'id','sender')->first();
        }
    }

    public function ticket(){
        return $this->hasOne(SupportTicket::class,'id','ticket_id');
    }

    public  static function change_status($ticket_id)
    {
        $ticket = SupportConversion::find($ticket_id);
        $ticket->status = 'In Progress';
        $ticket->update();
        return $ticket;
    }
}
