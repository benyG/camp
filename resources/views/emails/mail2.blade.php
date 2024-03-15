<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings xmlns:o="urn:schemas-microsoft-com:office:office">
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <style>
    td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
  </style>
  <![endif]-->
  <title>New Exam</title>
  <style>
    img {
      max-width: 100%;
      vertical-align: middle;
      line-height: 1
    }
    .hover-underline:hover {
      text-decoration-line: underline !important
    }
    .hover-important-text-decoration-underline:hover {
      text-decoration: underline !important
    }
    @media (max-width: 600px) {
      .sm-my-8 {
        margin-top: 32px !important;
        margin-bottom: 32px !important
      }
      .sm-px-4 {
        padding-left: 16px !important;
        padding-right: 16px !important
      }
      .sm-px-6 {
        padding-left: 24px !important;
        padding-right: 24px !important
      }
      .sm-leading-8 {
        line-height: 32px !important
      }
    }
    @media (prefers-color-scheme: dark) {
      .dark-text-green-400 {
        color: #4ade80 !important
      }
    }
  </style>
</head>
<body style="margin: 0; width: 100%; background-color: #f8fafc; padding: 0; -webkit-font-smoothing: antialiased; word-break: break-word">
    <div class="sm-px-4" style="background-color: #f8fafc; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif">
      <table align="center" cellpadding="0" cellspacing="0" role="none">
        <tr>
          <td style="width: 552px; max-width: 100%">
            <div class="sm-my-8" style="margin-top: 20px; margin-bottom: 20px; text-align: center; font-weight: 700">
            </div>
            <table style="width: 100%;" cellpadding="0" cellspacing="0" role="none">
              <tr>
                <td class="sm-px-6" style="border-radius: 4px; background-color: #fff; padding: 40px; font-size: 16px; color: #334155; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05)">
                  <h1 class="sm-leading-8" style="margin: 0 0 24px; font-size: 24px; font-weight: 600; color: #000">
                    {{ __('email.hello') }} <span style="color: #15803d">{{$name}}</span>,
                  </h1>
                  <p style="margin: 0; line-height: 24px">
                    {{ __('email.m10') }} <b>{{$para[0]}}
                    <br>
                        {{ __('main.ti') }} : {{$para[1]}}<br>
                        Certification : {{$para[3]}}<br>
                       {{ __('main.dd') }} : {{$para[2]}}</b>
                    <br>
                    <br>
                   {{ __('email.m11') }}.
                  </p>
                  <div role="separator" style="line-height: 10px">&zwj;</div>
                  <div>
                    <a href="{{\App\Filament\Resources\ExamResource::getUrl()}}" style="display: inline-block; border-radius: 4px; background-color: #15803d; padding: 16px 24px; font-size: 16px; font-weight: 600; line-height: 1; color: #f8fafc; text-decoration: none">
                      <!--[if mso]>
      <i style="mso-font-width: -100%; letter-spacing: 32px; mso-text-raise: 30px" hidden>&nbsp;</i>
    <![endif]-->
                      <span style="mso-text-raise: 16px">
                  {{ __('email.m12') }} &rarr;
                </span>
                      <!--[if mso]>
      <i style="mso-font-width: -100%; letter-spacing: 32px;" hidden>&nbsp;</i>
    <![endif]-->
                    </a>
                  </div>
                  <br>
                  {{ __('email.m8') }}, <br><span style="font-size: 16px; font-style: italic; color: #94a3b8">{{ __('email.m9') }}</span>
                  <div role="separator" style="background-color: #e2e8f0; height: 1px; line-height: 1px; margin: 20px 0">&zwj;</div>
                  <p style="margin: 0; font-size: 12px">
                   {{ __('email.m5') }} <a href="#" class="hover-underline dark-text-green-400" target="_blank" style="color: #15803d">{{$email}}</a>
                    <br>{{ __('email.m6') }}.
                  </p>
                </td>
              </tr>
              <tr role="separator">
                <td style="line-height: 8px">&zwj;</td>
              </tr>
              <tr>
                <td style="padding-left: 24px; padding-right: 24px; text-align: center; font-size: 12px; color: #475569">
                  <p style="margin: 0 0 4px; text-transform: uppercase">
                    <img src="{{$message->embed(public_path('img/logo-mail.png'))}}" style="width:220px"/>
                  </p>
                  <p style="margin: 0; font-style: italic;">
                    {{ __('email.m7') }}
                  </p>
                  <p style="cursor: default">
                    <a href="{{env('APP_URL')}}" class="hover-important-text-decoration-underline" style="color: #4338ca; text-decoration: none">{{ __('main.home') }}</a>
                    &bull;
                    <a href="#" class="hover-important-text-decoration-underline" style="color: #4338ca; text-decoration: none;">Docs</a>
                    &bull;
                    <a href="#" class="hover-important-text-decoration-underline" style="color: #4338ca; text-decoration: none;">LinkedIn</a>
                    &bull;
                    <a href="#" class="hover-important-text-decoration-underline" style="color: #4338ca; text-decoration: none;">Twitter</a>
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
</body>
</html>