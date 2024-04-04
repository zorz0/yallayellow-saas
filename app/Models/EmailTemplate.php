<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'from', 'created_by'
    ];

    public function template()
    {
        return $this->hasOne(UserEmailTemplate::class, 'template_id', 'id')->where('user_id', auth()->user()->id);
    }

    public static $email_settings = [
        "custom" => "Custom",
        "smtp" => "SMTP",
        "gmail" => "Gmail",
        "outlook" => "Outlook/Office 365",
        "yahoo" => "Yahoo",
        "sendgrid" => "SendGrid",
        "amazon" => "Amazon SES ",
        "mailgun" => "Mailgun",
        "smtp.com" => "SMTP.com",
        "zohomail" => "Zoho Mail",
        "mandrill" => "Mandrill",
        "mailtrap" => "Mailtrap",
        "sparkpost" => "SparkPost"
    ];
}
