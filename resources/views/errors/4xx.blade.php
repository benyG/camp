 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
 <!-- saved from url=(0014)about:internet -->
 <html>

 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
     <title>Exam Boot</title>
     <style type="text/css">
         html,
         body {
             height: 100%;
         }

         div#space {
             width: 1px;
             height: 50%;
             margin-bottom: -400px;
             float: left
         }

         div#container {
             width: 1024px;
             height: 800px;
             margin: 0 auto;
             position: relative;
             clear: left;
         }
     </style>
     <style type="text/css">
         body {
             margin: 0;
             padding: 0;
             background-color: #18181B;
             color: #000000;
         }
     </style>
     <style type="text/css">
         a {
             color: #22C55E;
         }

         a:visited {
             color: #22C55E;
         }

         a:active {
             color: #00FF00;
         }

         a:hover {
             color: #f1ce6a;
         }
     </style>
     <!--[if lt IE 7]>
<style type="text/css">
   img { behavior: url("pngfix.htc"); }
</style>
<![endif]-->
 </head>

 <body>
     <div id="space"><br></div>
     <div id="container">
         <div id="bv_Text2"
             style="margin:0;padding:0;position:absolute;left:295px;top:216px;width:361px;height:44px;text-align:left;z-index:0;">
             <font style="font-size:37px" color="#FFFFFF" face="Arial"><b>{{ $exception->getStatusCode() }} </b>-
             </font>
             <font style="font-size:29px" color="#FFFFFF" face="Arial">ERROR</font>
         </div>
         <div id="Layer1"
             style="position:absolute;background-color:#22C55E;left:197px;top:0px;width:53px;height:309px;z-index:1"
             title="">
         </div>
         <div id="bv_Image1"
             style="margin:0;padding:0;position:absolute;left:261px;top:123px;width:252px;height:37px;text-align:left;z-index:2;">
             <img src="{{ asset('img/logo-default-slim-dark.png') }}" id="Image1" alt="" align="top"
                 border="0" style="width:252px;">
         </div>
         <div id="bv_Text1"
             style="margin:0;padding:0;position:absolute;left:294px;top:292px;width:560px;height:44px;text-align:left;z-index:3;">
             <br> @switch($exception->getStatusCode())
                 @case(404)
                     <font style="font-size:19px" color="#FFFFFF" face="Arial">Oops! {{ __('main.e1') }}.<br><br>
                         <a href="{{ env('APP_URL') }}" target="_self">{{ __('main.e1') }}</a> {{ __('main.e3') }}.
                     </font>
                 @break

                 @case(419)
                     <font style="font-size:19px" color="#FFFFFF" face="Arial">{{ __('main.e4') }}.<br>
                     </font>
                 @break

                 @case(403)
                     <font style="font-size:19px" color="#FFFFFF" face="Arial">{{ __('main.e7') }}.<br>
                     </font>
                 @break

                 @default
                     {{ $exception->getMessage() }}
             @endswitch

         </div>
     </div>
 </body>

 </html>
