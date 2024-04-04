<div class="row">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="mail_driver" class="form-label">{{ __('Mail Driver') }}</label>
            {{ Form::text('mail_driver', isset($setting['MAIL_DRIVER']) ? $setting['MAIL_DRIVER'] : null,  ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver'), 'id' => 'mail_driver']) }}

        </div>
        @if ($email_setting == 'custom')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            {{ Form::text('mail_host', isset($setting['MAIL_HOST']) ? $setting['MAIL_HOST'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Host'), 'id' => 'mail_host']) }}
        </div>
        @elseif ($email_setting == 'gmail')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.gmail.com" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'outlook')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.office365.com" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'yahoo')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.mail.yahoo.com" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'sendgrid')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.sendgrid.net" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'amazon')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="email-smtp.us-east-1.amazonaws.com" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'mailgun')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.mailgun.org" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'smtp.com')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.smtp.com" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'zohomail')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.zoho.com" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'mailtrap')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.mailtrap.io" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'mandrill')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.mandrillapp.com" class="form-control" readonly="readonly" >
        </div>
        @elseif ($email_setting == 'smtp')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            {{ Form::text('mail_host', isset($setting['MAIL_HOST']) ? $setting['MAIL_HOST'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Host'), 'id' => 'mail_host']) }}
        </div>
        @elseif ($email_setting == 'sparkpost')
        <div class="form-group col-md-4">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" id="mail_host" value="smtp.sparkpostmail.com" class="form-control" readonly="readonly" >

        </div>
        @endif

        @if ($email_setting == 'custom')
        <div class="form-group col-md-4">
            <label for="mail_port" class="form-label">{{ __('Mail Port') }}</label>
            {{ Form::text('mail_port', isset($setting['MAIL_PORT']) ? $setting['MAIL_PORT'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Port'), 'id' => 'mail_port']) }}
        </div>
        @elseif ($email_setting == 'smtp')
        <div class="form-group col-md-4">
            <label for="mail_port" class="form-label">{{ __('Mail Port') }}</label>
            {{ Form::text('mail_port',  isset($setting['MAIL_PORT']) ? $setting['MAIL_PORT'] : null,  ['class' => 'form-control', 'placeholder' => __('Enter Mail Port'), 'id' => 'mail_port']) }}
        </div>
        @else
        <div class="form-group col-md-4">
            <label for="mail_port" class="form-label">{{ __('Mail Port') }}</label>
            <input type="text" name="mail_port" id="mail_port" value="587" class="form-control" readonly="readonly" >
        </div>
        @endif
        @if ($email_setting == 'custom')
        <div class="form-group col-md-4">
                  <label for="mail_username" class="form-label">{{ __('Mail Username') }}</label>
                  {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Enter mail username'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'gmail')
              <div class="form-group col-md-4">
                  <label for="mail_username" class="form-label">{{ __('Your Gmail email address') }}</label>
                  {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your Gmail email address'), 'id' => 'mail_username']) }}
              </div>
        @elseif ($email_setting == 'smtp')
        <div class="form-group col-md-4">
          <label for="mail_username" class="form-label">{{ __('Mail Username') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Enter mail username'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'outlook')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __(' Your Outlook/Office 365 email address') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __(' Your Outlook/Office 365 email address'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'yahoo')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your Yahoo email address') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your Yahoo email address'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'sendgrid')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your SendGrid username or API key') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your SendGrid username or API key'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'amazon')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your AWS IAM username or access key ID') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your AWS IAM username or access key ID'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'mailgun')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your Mailgun SMTP username') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your Mailgun SMTP username'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'smtp.com')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your Mailgun SMTP username') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your Mailgun SMTP username'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'zohomail')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your Zoho Mail email address') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your Zoho Mail email address'), 'id' => 'mail_username']) }}
        </div>

        @elseif ($email_setting == 'mandrill')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your Mandrill API key') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your Mandrill API key'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'mailtrap')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your Mailtrap username') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your Mailtrap username'), 'id' => 'mail_username']) }}
        </div>
        @elseif ($email_setting == 'sparkpost')
        <div class="form-group col-md-4">
        <label for="mail_username" class="form-label">{{ __('Your SparkPost SMTP username') }}</label>
          {{ Form::text('mail_username',   isset($setting['MAIL_USERNAME']) ? $setting['MAIL_USERNAME'] : null,  ['class' => 'form-control', 'placeholder' => __('Your SparkPost SMTP username'), 'id' => 'mail_username']) }}
        </div>
        @endif


        <div class="form-group col-md-4">
            <label for="mail_password" class="form-label">{{ __('Mail Password') }}</label>
            {{ Form::text('mail_password',  isset($setting['MAIL_PASSWORD']) ? $setting['MAIL_PASSWORD'] : null,  ['class' => 'form-control', 'placeholder' => __('Enter mail password'), 'id' => 'mail_password']) }}
        </div>
        @if ($email_setting == 'custom')
        <div class="form-group col-md-4">
            <label for="" class="form-label">{{ __('Mail Encryption') }}</label>
            {{ Form::text('mail_encryption',isset($setting['MAIL_ENCRYPTION']) ? $setting['MAIL_ENCRYPTION'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption'), 'id' => 'mail_encryption']) }}
        </div>
        @elseif ($email_setting == 'smtp')
        <div class="form-group col-md-4">
            <label for="mail_encryption" class="form-label">{{ __('Mail Encryption') }}</label>
            {{ Form::text('mail_encryption',isset($setting['MAIL_ENCRYPTION']) ? $setting['MAIL_ENCRYPTION'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption'), 'id' => 'mail_encryption']) }}
        </div>
        @else
        <div class="form-group col-md-4">
            <label for="mail_encryption" class="form-label">{{ __('Mail Encryption') }}</label>
            <input type="text" name="mail_encryption" id="mail_encryption" value="TLS" class="form-control" readonly="readonly" >
        </div>
        @endif
        <div class="form-group col-md-4">
            <label for="mail_from_address" class="form-label">{{ __('Mail From Address') }}</label>
            {{ Form::text('mail_from_address',isset($setting['MAIL_FROM_ADDRESS']) ? $setting['MAIL_FROM_ADDRESS'] : null, ['class' => 'form-control ', 'placeholder' => __('Enter mail from address'), 'id' => 'mail_from_address']) }}
        </div>
        <div class="form-group col-md-4">
            <label for="mail_from_name" class="form-label">{{ __('Mail From Name') }}</label>
            {{ Form::text('mail_from_name', isset($setting['MAIL_FROM_NAME']) ? $setting['MAIL_FROM_NAME'] : null, ['class' => 'form-control', 'placeholder' => __('Enter mail from name'), 'id' => 'mail_from_name']) }}
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('input[readonly="readonly"]').css('background-color', '#e9ecef');
    });
</script>