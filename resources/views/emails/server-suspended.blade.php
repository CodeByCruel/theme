<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Server Suspended</title>
    <style>
        body, table, td, p, a, li, blockquote { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; }
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .fluid { max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important; }
            .stack-column, .stack-column-center { display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important; }
            .stack-column-center { text-align: center !important; }
            .mobile-padding { padding-left: 20px !important; padding-right: 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0c0a09; font-family: Inter, system-ui, -apple-system, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #0c0a09;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <table class="email-container" role="presentation" cellspacing="0" cellpadding="0" border="0" width="560" style="max-width: 560px; margin: 0 auto;">
                    {{-- Logo --}}
                    <tr>
                        <td align="center" style="padding-bottom: 32px;">
                            <a href="{{ url('/') }}" target="_blank" style="text-decoration: none;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td style="background-color: #df3050; border-radius: 12px; padding: 10px 20px; text-align: center;">
                                            <span style="font-family: Inter, system-ui, sans-serif; font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">BSDK</span>
                                        </td>
                                    </tr>
                                </table>
                            </a>
                        </td>
                    </tr>

                    {{-- Card --}}
                    <tr>
                        <td>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #191919; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08); overflow: hidden;">
                                {{-- Header --}}
                                <tr>
                                    <td style="padding: 40px 40px 0 40px;" class="mobile-padding">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            {{-- Warning Icon --}}
                                            <tr>
                                                <td align="center" style="padding-bottom: 20px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="width: 56px; height: 56px; border-radius: 50%; background-color: rgba(223,48,80,0.15); text-align: center; line-height: 56px;">
                                                                <span style="font-size: 28px; line-height: 56px;">&#9888;</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: Inter, system-ui, sans-serif; font-size: 24px; font-weight: 700; color: #df3050; text-align: center; padding-bottom: 16px;">
                                                    Server Suspended
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: Inter, system-ui, sans-serif; font-size: 15px; line-height: 24px; color: #a1a1aa; text-align: center;">
                                                    Your server has been suspended and is no longer accessible. Please review the details below.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Server & Suspension Details Card --}}
                                <tr>
                                    <td style="padding: 32px 40px;" class="mobile-padding">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #0c0a09; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                                            {{-- Server Name --}}
                                            <tr>
                                                <td style="padding: 20px 24px 0 24px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="font-family: Inter, system-ui, sans-serif; font-size: 12px; font-weight: 600; color: #a1a1aa; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 4px;">
                                                                Server
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: Inter, system-ui, sans-serif; font-size: 15px; font-weight: 600; color: #ffffff;">
                                                                {{ $server->name ?? 'Your Server' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            {{-- Divider --}}
                                            <tr>
                                                <td style="padding: 16px 24px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="border-top: 1px solid rgba(255,255,255,0.08); font-size: 0; line-height: 0;">&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            {{-- Reason --}}
                                            <tr>
                                                <td style="padding: 0 24px 20px 24px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="font-family: Inter, system-ui, sans-serif; font-size: 12px; font-weight: 600; color: #a1a1aa; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 4px;">
                                                                Reason
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: Inter, system-ui, sans-serif; font-size: 15px; line-height: 24px; color: #ffffff;">
                                                                {{ $server->suspension_reason ?? 'No reason provided. Contact support for details.' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- CTA Button --}}
                                <tr>
                                    <td style="padding: 0 40px 40px 40px;" class="mobile-padding">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td align="center">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="border-radius: 10px; border: 1px solid rgba(255,255,255,0.15);">
                                                                <a href="{{ url('/support') }}" target="_blank" style="display: inline-block; padding: 14px 40px; font-family: Inter, system-ui, sans-serif; font-size: 15px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 10px; background-color: transparent; border: 1px solid rgba(255,255,255,0.15);">
                                                                    Contact Support
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 32px 40px 0 40px;" class="mobile-padding">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="font-family: Inter, system-ui, sans-serif; font-size: 13px; line-height: 20px; color: #a1a1aa; text-align: center;">
                                        {{ $appName ?? 'BSDK Panel' }} &mdash; Game Server Management
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 12px; font-family: Inter, system-ui, sans-serif; font-size: 12px; line-height: 18px; color: #52525b; text-align: center;">
                                        <a href="{{ url('/unsubscribe') }}" target="_blank" style="color: #52525b; text-decoration: underline;">Unsubscribe</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
