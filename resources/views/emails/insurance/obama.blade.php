<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    {{-- <title>{{ config('insura.name') }} | {{ trans('emails.title.welcome') }}</title> --}}
  </head>
  <body style="margin:0px; background: #f8f8f8; ">
    <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
      <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
          <tbody>
            <tr>
              <td style="vertical-align: top; padding-bottom:30px;" align="center">
                {{-- <a href="{{ action('IndexController@get') }}" target="_blank"> --}}
                  {{-- <img src="{{ asset('uploads/images/'. config('insura.favicon')) }}" alt="{{ config('insura.name') }} Logo" style="border:none;height:50px;"><br/> --}}
                </a>
              </td>
            </tr>
          </tbody>
        </table>
        <div style="padding: 40px; background: #fff;">
          <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
            <tbody>
              <tr>
                <td style=" font-family: 'Montserrat', system, 'Verdana', sans-serif; font-size: 15px; color:white; text-align: center; "
                    class="center-on-narrow">
                    <a href="{{config('app.url')}}">
                      <div style="text-align: center"><img src="{{asset('images')}}/logo_pdf2.jpg" width="200" class="rounded" /></div>
                    </a>
                </td>
            </tr>
              <tr>
                <td style="border-bottom:1px solid #f6f6f6;">
                  <div style="text-align: right; margin-top: 15px"><strong>{{ \Carbon\Carbon::parse(now())->format('m/d/Y') ?? '-----'}}</strong></div>
                  <div style="margin-top: 30px"><strong>Obama Care tipo:</strong> {{ $data->insurance ?? 'Nuevo'}} </div>
                  <div style="margin-top: 10px"><strong>Compañía:</strong> {{$data->user->companyName ?? '-----'}}</div>
                  <div style="margin-top: 10px"><strong>Agente:</strong> {{$data->user->fullName}}</div>
                  <div style="margin-top: 10px"><strong>Cliente:</strong> {{$data->client->fullName}}</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
