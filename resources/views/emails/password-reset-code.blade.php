<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu TourTravel</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f1f5f9;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 520px; margin: 0 auto;">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981, #059669); padding: 32px 40px; border-radius: 24px 24px 0 0; text-align: center;">
                            <div style="display: inline-block; width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 14px; line-height: 48px; margin-bottom: 16px;">
                                <span style="font-size: 22px;">✈️</span>
                            </div>
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.5px;">
                                TourTravel
                            </h1>
                            <p style="color: rgba(255,255,255,0.85); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 3px; margin: 0;">
                                Premium Travel Experience
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;">
                            <h2 style="color: #0f172a; font-size: 20px; font-weight: 800; margin: 0 0 8px 0; text-align: center;">
                                Đặt lại mật khẩu
                            </h2>
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 0 0 28px 0; text-align: center;">
                                Xin chào <strong style="color: #0f172a;">{{ $userName }}</strong>,<br>
                                Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.
                            </p>

                            <!-- OTP Code -->
                            <div style="background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 2px solid #bbf7d0; border-radius: 16px; padding: 28px; text-align: center; margin-bottom: 28px;">
                                <p style="color: #059669; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 3px; margin: 0 0 12px 0;">
                                    Mã xác thực của bạn
                                </p>
                                <div style="font-size: 36px; font-weight: 900; color: #059669; letter-spacing: 8px; font-family: 'Courier New', monospace; margin: 0;">
                                    {{ $otpCode }}
                                </div>
                            </div>

                            <!-- Timer -->
                            <div style="background-color: #fefce8; border: 1px solid #fde68a; border-radius: 12px; padding: 14px 20px; margin-bottom: 24px; text-align: center;">
                                <p style="color: #92400e; font-size: 13px; font-weight: 600; margin: 0;">
                                    ⏰ Mã có hiệu lực trong <strong>{{ $expireMinutes }} phút</strong>
                                </p>
                            </div>

                            <!-- Instructions -->
                            <div style="border-top: 1px solid #f1f5f9; padding-top: 24px;">
                                <p style="color: #475569; font-size: 13px; line-height: 1.7; margin: 0 0 16px 0;">
                                    Nhập mã xác thực trên vào trang đặt lại mật khẩu để hoàn tất việc cập nhật.
                                </p>

                                <!-- Security Warning -->
                                <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px 20px;">
                                    <p style="color: #991b1b; font-size: 12px; font-weight: 600; margin: 0;">
                                        🔒 <strong>Lưu ý bảo mật:</strong> Không chia sẻ mã này với bất kỳ ai. Nhân viên TourTravel không bao giờ yêu cầu mã xác thực của bạn.
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 40px; border-radius: 0 0 24px 24px; border-top: 1px solid #e2e8f0;">
                            <p style="color: #94a3b8; font-size: 11px; line-height: 1.6; margin: 0; text-align: center;">
                                Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
                                Tài khoản của bạn vẫn an toàn.
                            </p>
                            <p style="color: #cbd5e1; font-size: 10px; margin: 16px 0 0 0; text-align: center; letter-spacing: 1px;">
                                © {{ date('Y') }} TourTravel. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
