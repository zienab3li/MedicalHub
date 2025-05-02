<div style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="color: #2b9348;">🎉 Congratulations! You've Received a Discount Coupon</h2>
    
    <p style="font-size: 16px;">
        <strong>Coupon Code:</strong> 
        <span style="color: #d62828; font-weight: bold;">{{ $coupon->code }}</span>
    </p>

    <p style="font-size: 16px;">
        <strong>Discount Type:</strong> 
        {{ $coupon->discount_type === 'percentage' ? 'Percentage' : 'Fixed Amount' }}
    </p>

    <p style="font-size: 16px;">
        <strong>Discount Value:</strong> 
        {{ $coupon->discount_value }} {{ $coupon->discount_type === 'percentage' ? '%' : 'EGP' }}
    </p>

    <p style="font-size: 16px;">
        <strong>Valid Until:</strong> 
        {{ $coupon->expires_at->format('F j, Y') }}
    </p>

    <hr style="margin: 20px 0;">

    <p style="font-size: 15px; color: #555;">
        Don’t miss this special offer — apply your coupon at checkout and enjoy the savings!
    </p>
</div>
