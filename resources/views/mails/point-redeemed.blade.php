<div style="font-family: 'Helvetica Neue', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333333;">

    <h2 style="text-align: center; color: #333333; font-weight: bold;">แจ้งเตือนการใช้แต้มสะสม 💸</h2>

    <div style="text-align: center; margin: 40px 0;">
        <span style="font-size: 32px; color: #e53935; font-weight: bold;">
            -{{ $amount }} แต้ม
        </span><br>
        <span style="font-size: 16px; color: #666666; display: inline-block; margin-top: 8px;">
            ถูกใช้ไปสำหรับการทำรายการของคุณ
        </span>
    </div>

    <div style="background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 30px;">
        <span style="font-size: 16px; color: #555555;">ยอดแต้มคงเหลือของคุณคือ:</span><br>
        <strong style="font-size: 24px; color: #1565c0; display: inline-block; margin-top: 5px;">
            {{ $balance }} แต้ม
        </strong>
    </div>

    <p style="font-size: 14px; color: #555555;">หากคุณไม่ได้เป็นผู้ทำรายการนี้ กรุณาติดต่อฝ่ายบริการลูกค้าทันที</p>

    <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">

    <p style="font-size: 14px; color: #888888;">
        ขอบคุณที่ใช้บริการครับ,<br>
        <strong>{{ config('app.name') }}</strong>
    </p>
</div>
