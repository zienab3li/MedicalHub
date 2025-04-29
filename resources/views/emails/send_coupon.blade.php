<h2>🎉 مبروك! حصلت على كوبون خصم</h2>
<p>كود الكوبون: <strong>{{ $coupon->code }}</strong></p>
<p>نوع الخصم: {{ $coupon->discount_type === 'percentage' ? 'نسبة' : 'قيمة ثابتة' }}</p>
<p>قيمة الخصم: {{ $coupon->discount_value }} {{ $coupon->discount_type === 'percentage' ? '%' : 'جنيه' }}</p>
<p>انتهز الفرصة قبل {{ $coupon->expires_at->format('Y-m-d') }}</p>
